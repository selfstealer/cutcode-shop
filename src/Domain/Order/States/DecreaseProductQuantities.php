<?php

declare(strict_types=1);

namespace Domain\Order\States;

use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Models\Order;
use Illuminate\Support\Facades\DB;

class DecreaseProductQuantities implements OrderProcessContract
{

    public function handle(Order $order, $next)
    {
        foreach (cart()->items() as $item) {
            $item->product()->update([
                'quantity' => DB::raw('quantity - '. $item->quantity)
            ]);
        }

        return $next($order);
    }
}
