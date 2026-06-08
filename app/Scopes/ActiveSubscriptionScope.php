<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * ActiveSubscriptionScope
 *
 * Automatically applied to EVERY TechnicianProfile query via the
 * #[ScopedBy] attribute on the model.
 *
 * This means you NEVER need to write ->whereHas('subscription') anywhere.
 * Technicians without an active subscription are invisible to customers
 * across the entire application automatically.
 *
 * To bypass (e.g., in admin panels):
 *   TechnicianProfile::withoutGlobalScope(ActiveSubscriptionScope::class)->get()
 */
class ActiveSubscriptionScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereHas('user', function (Builder $q) {
            $q->whereHas('subscriptions', function (Builder $q) {
                $q->where('status', 'active')
                  ->where('expires_at', '>', now());
            });
        });
    }
}
