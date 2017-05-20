<?php

$this->schema->dropIfExists('books');

$this->schema->create('books', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->string('title');
    $table->timestamps();
});

$this->schema->dropIfExists('authors');

$this->schema->create('authors', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
});

$this->schema->dropIfExists('author_book');

$this->schema->create('author_book', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->integer('book_id')->unsigned()->index()->notnull();
    $table->integer('author_id')->unsigned()->index()->notnull();
    $table->timestamps();

    $table->unique('book_id', 'author_id');
});
