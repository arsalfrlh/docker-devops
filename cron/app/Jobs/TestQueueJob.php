<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestQueueJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('hls');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('TEST QUEUE HLS DEFAULT BERHASIL', [
            'time' => now()->toDateTimeString(),
        ]);
    }
}
