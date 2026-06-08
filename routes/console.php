<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Scheduled Commands
 *
 * Make sure the scheduler is running on your server:
 *   Windows Task Scheduler: php artisan schedule:run (every minute)
 *   Linux cron: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
 */

// Expire subscriptions that have passed their expires_at date
Schedule::command('subscriptions:expire')->daily()->at('00:05');
