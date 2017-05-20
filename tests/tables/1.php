<?php

$this->schema->dropIfExists('users');

$this->schema->create('users', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->timestamps();
});

$this->schema->dropIfExists('orders');

$this->schema->create('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned()->index();
    $table->integer('courier_id')->unsigned()->index()->nullable();
    $table->decimal('total', 7, 2);
    $table->timestamps();
});

$this->schema->dropIfExists('order_products');

$this->schema->create('order_products', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->integer('order_id')->unsigned()->index();
    $table->integer('quantity')->unsigned();
    $table->timestamps();
});

$this->schema->dropIfExists('couriers');

$this->schema->create('couriers', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->increments('id');
    $table->timestamps();
});
