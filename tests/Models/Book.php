<?php

namespace Driade\Fixtures\Test\Models;

class Book extends \Illuminate\Database\Eloquent\Model
{
    public function authors()
    {
        return $this->belongsToMany('Driade\Fixtures\Test\Models\Author');
    }
}
