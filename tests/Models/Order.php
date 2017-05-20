<?php

namespace Driade\Fixtures\Test\Models;

class Order extends \Illuminate\Database\Eloquent\Model
{
    public function products()
    {
        return $this->hasMany('Driade\Fixtures\Test\Models\OrderProduct');
    }

    public function user()
    {
        return $this->belongsTo('Driade\Fixtures\Test\Models\User');
    }

    public function courier()
    {
        return $this->belongsTo('Driade\Fixtures\Test\Models\Courier');
    }
}
