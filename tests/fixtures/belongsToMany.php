<?php

return [
    'Driade\Fixtures\Test\Models\Author',
    'name'  => 'J.R.R. Tolkien',
    'books' => [
        [
            'Driade\Fixtures\Test\Models\Book',
            'title' => 'The Lord of the Rings',
        ],
        [
            'Driade\Fixtures\Test\Models\Book',
            'title' => 'The Hobbit',
        ],
    ],
];
