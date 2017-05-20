<?php

namespace Driade\Fixtures\Test\Models;

class Author extends \Illuminate\Database\Eloquent\Model
{
    public function books()
    {
        return $this->belongsToMany('Driade\Fixtures\Test\Models\Book');
    }
}
