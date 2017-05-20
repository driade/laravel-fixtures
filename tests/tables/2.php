<?php

$this->schema->dropIfExists('owners');

$this->schema->create('owners', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->timestamps();
});

$this->schema->dropIfExists('dogs');

$this->schema->create('dogs', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->integer('owner_id')->unsigned()->index();
    $table->timestamps();
});
