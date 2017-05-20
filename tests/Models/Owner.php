<?php

namespace Driade\Fixtures\Test\Models;

class Owner extends \Illuminate\Database\Eloquent\Model
{
    public function dog()
    {
        return $this->hasOne('Driade\Fixtures\Test\Models\Dog');
    }
}
