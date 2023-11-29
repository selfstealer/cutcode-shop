<?php

declare(strict_types=1);

namespace Domain\Order\Processes;

use Domain\Order\Events\OrderCreated;
use Domain\Order\Models\Order;
use DomainException;
use Illuminate\Pipeline\Pipeline;
use Support\Traits\Makeable;
use Support\Transaction;
use Throwable;

class OrderProcess
{
    use Makeable;

    protected array $processes = [];

    public function __construct(
        protected Order $order
    )
    {
    }

    public function processes(array $processes): self
    {
        $this->processes = $processes;

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function run(): Order
    {
        return Transaction::run(
            function () {
                return app(Pipeline::class)
                    ->send($this->order)
                    ->through($this->processes)
                    ->thenReturn();
            },
            function (Order $order) {
                flash()->info('Good # '. $order->id);

                event(new OrderCreated($order));
            },
            function (Throwable $e) {
                // не для production
                throw new DomainException($e->getMessage());
            }
        );
    }
}
