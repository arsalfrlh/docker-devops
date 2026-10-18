<?php

namespace App\Jobs;

use App\Models\ProductMedia;
use FFMpeg\Format\Video\X264;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Throwable;

class VideoHlsProcess implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable;
    protected $mediaId;
    public int $tries = 3;
    public array $backoff = [10, 30, 60];
    public int $timeout = 1800;

    /**
     * Create a new job instance.
     */
    public function __construct($mediaId)
    {
        $this->mediaId = $mediaId;
        $this->onQueue('hls');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $media = ProductMedia::findOrFail($this->mediaId);
        $mediaPath = $media->media_path;
        $fileName = "playlist.m3u8";
        $filePath = "videos/hls_" . $media->id;
        $format = new X264('aac','libx264');
        $format->setAudioCodec('aac');
        FFMpeg::fromDisk('public')->open($mediaPath)->exportForHLS()->setSegmentLength(10)->addFormat($format)->toDisk('public')->save($filePath . '/' . $fileName);
        $duration = FFMpeg::fromDisk('public')->open($mediaPath)->getDurationInSeconds();
        $media->update([
            'media_path' => $filePath . '/' . $fileName,
            'duration' => $duration
        ]);
        Storage::disk('public')->delete($mediaPath);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('VideoHlsProcess FAILED', [
            'media_id' => $this->mediaId,
            'exception' => $exception?->getMessage(),
            'class' => $exception ? get_class($exception) : null,
            'trace' => $exception?->getTraceAsString(),
        ]);
    }
}
