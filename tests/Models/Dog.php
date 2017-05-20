<?php

namespace Driade\Fixtures\Test\Models;

class Dog extends \Illuminate\Database\Eloquent\Model
{
    public function owner()
    {
        return $this->belongsTo('Driade\Fixtures\Test\Models\Owner');
    }
}
