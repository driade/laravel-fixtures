<?php

$this->schema->dropIfExists('photos');

$this->schema->create('photos', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('photos');
    $table->integer('imageable_id')->unsigned()->index()->notnull();
    $table->string('imageable_type')->index()->notnull();
    $table->timestamps();
});

$this->schema->dropIfExists('staff');

$this->schema->create('staff', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->timestamps();
});

$this->schema->dropIfExists('persons');

$this->schema->create('persons', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->timestamps();
});
