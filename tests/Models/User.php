<?php

namespace Driade\Fixtures\Test\Models;

class User extends \Illuminate\Database\Eloquent\Model
{
    public function orders()
    {
        return $this->hasMany('Driade\Fixtures\Test\Models\Order');
    }
}
