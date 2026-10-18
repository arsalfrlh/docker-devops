<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('order:expire')]
#[Description('Expire pending order')]
class ExpirePendingOrder extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $count = Order::where('status','pending')->whereNotNull('status')->where('expired_at', '<=', $now)->update([
            'status' => 'expire'
        ]);

        Log::info("Expired Payment", [
            'count' => $count,
            'checked_at' => $now->toDateTimeString()
        ]);
        $this->info("Expired $count payments");
        return self::SUCCESS;
    }
}
