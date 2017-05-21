<?php

namespace Driade\Fixtures\Test\Models;

return [
    User::class,
    'orders' => [
        [
            Order::class,
            'total'    => 10,
            'courier'  => [
                Courier::class,
            ],
            'products' => [
                [
                    OrderProduct::class,
                    'quantity' => 1,
                ],
            ],
        ],
    ],
];
