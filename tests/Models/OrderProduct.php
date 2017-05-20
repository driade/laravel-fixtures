<?php

namespace Driade\Fixtures\Test\Models;

class OrderProduct extends \Illuminate\Database\Eloquent\Model
{
    public function order()
    {
        return $this->belongsTo('Driade\Fixtures\Test\Models\Order');
    }
}
