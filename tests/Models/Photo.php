<?php

namespace Driade\Fixtures\Test\Models;

class Photo extends \Illuminate\Database\Eloquent\Model
{
    public function imageable()
    {
        return $this->morphTo();
    }
}
