<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SubscriptionMiddleware
 *
 * Inazuia au kuruhusu mafundi kulingana na hali ya usajili wao.
 * Kama yuko active, anapelekwa Dashboard moja kwa moja.
 */
class SubscriptionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Inafanya kazi kwa mafundi (technicians) tu walioingia kwenye mfumo
        if ($user?->isTechnician()) {
            
            // 1. ANGALIA KAMA ANASUBCRIPTION HAI (YUKO ACTIVE)
            if ($user->hasActiveSubscription()) {
                
                // Kama yuko active na anajaribu kwenda kwenye kurasa za malipo, mpeleke Dashboard moja kwa moja
                if ($request->routeIs('technician.subscription.*')) {
                    return redirect()->route('technician.dashboard');
                }
                
                // Kama yuko active na yupo kwenye njia sahihi, mruhusu aendelee
                return $next($request);
            }

            // 2. KAMA HANA SUBSCRIPTION HAI (YUKO INACTIVE)
            // Ruhusu afungue kurasa za malipo tu ili aweze kulipia
            if ($request->routeIs('technician.subscription.*')) {
                return $next($request);
            }

            // Kama hana subscription na anajaribu kwenda dashboard au kwingine, mru hishe kwenye malipo
            return redirect()
                ->route('technician.subscription.index')
                ->with('warning', 'your subscription have been reached end or have not been checked buy admin otherwise please get new subscription!.');
        }

        return $next($request);
    }
}