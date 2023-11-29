<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use Domain\Order\Actions\NewOrderAction;
use Domain\Order\Models\DeliveryType;
use Domain\Order\Models\PaymentMethod;
use Domain\Order\Processes\AssignCustomer;
use Domain\Order\Processes\AssignProducts;
use Domain\Order\Processes\ChangeStateToPending;
use Domain\Order\Processes\CheckProductQuantities;
use Domain\Order\Processes\ClearCart;
use Domain\Order\Processes\OrderProcess;
use Domain\Order\States\DecreaseProductQuantities;
use DomainException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Throwable;

class OrderController extends Controller
{
    public function index(): Application|Factory|View
    {
        $items = cart()->items();

        if($items->isEmpty()) {
            throw new DomainException('Корзина пуста');
        }

        return view('order.index', [
            'items' => $items,
            'payments' => PaymentMethod::query()->get(),
            'deliveries' => DeliveryType::query()->get(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function handle(OrderFormRequest $request, NewOrderAction $action): RedirectResponse
    {
        $order = $action($request);

        OrderProcess::make($order)->processes([
            new CheckProductQuantities(),
            new AssignCustomer(request('customer')),
            new AssignProducts(),
            new ChangeStateToPending(),
            new DecreaseProductQuantities(),
            new ClearCart(),
        ])->run();

        return redirect()
            ->route('home');
    }
}
