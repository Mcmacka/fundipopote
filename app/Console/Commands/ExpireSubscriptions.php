<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

/**
 * ExpireSubscriptions
 *
 * Scheduled daily via routes/console.php:
 *   Schedule::command('subscriptions:expire')->daily();
 *
 * Run manually:
 *   php artisan subscriptions:expire
 */
class ExpireSubscriptions extends Command
{
    protected $signature   = 'subscriptions:expire';
    protected $description = 'Mark all past-due active subscriptions as expired';

    public function handle(): int
    {
        $count = Subscription::query()
            ->where('status', 'active')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("✅ Subscriptions zilizokwisha: {$count}");

        return self::SUCCESS;
    }
}
