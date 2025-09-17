<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserDiscount;
use Carbon\Carbon;

class UpdateExpiredDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discounts:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired user discounts status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating expired discounts...');

        $expiredDiscounts = UserDiscount::where('status', 'active')
            ->whereHas('discount', function ($query) {
                $query->where('expires_at', '<', Carbon::now());
            })
            ->get();

        $count = 0;
        foreach ($expiredDiscounts as $userDiscount) {
            $userDiscount->markAsExpired();
            $count++;
        }

        $this->info("Updated {$count} expired discounts.");
    }
} 