<?php

return [
    'Driade\Fixtures\Test\Models\User',
    'orders' => [
        [
            'Driade\Fixtures\Test\Models\Order',
            'total'    => 1,
            'products' => [
                [
                    'Driade\Fixtures\Test\Models\OrderProduct',
                    'quantity' => 1,
                ],
                [
                    'Driade\Fixtures\Test\Models\OrderProduct',
                    'quantity' => 2,
                ],
            ],
        ],
        [
            'Driade\Fixtures\Test\Models\Order',
            'total'    => 2,
            'products' => [
                [
                    'Driade\Fixtures\Test\Models\OrderProduct',
                    'quantity' => 3,
                ],
                [
                    'Driade\Fixtures\Test\Models\OrderProduct',
                    'quantity' => 4,
                ],
            ],
        ],
        [
            'Driade\Fixtures\Test\Models\Order',
            'total'    => 3,
            'products' => [
                [
                    'Driade\Fixtures\Test\Models\OrderProduct',
                    'quantity' => 5,
                ],
                [
                    'Driade\Fixtures\Test\Models\OrderProduct',
                    'quantity' => 6,
                ],
            ],
        ],
    ],
];
