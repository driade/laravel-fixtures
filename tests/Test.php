<?php

namespace Driade\Fixtures\Test;

use Driade\Fixtures\FixtureLoader;
use Illuminate\Database\Capsule\Manager as Capsule;

class Test extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver'    => 'sqlite',
            'database'  => __DIR__ . '/database.sqlite',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();

    }

    private function loadSeed($number)
    {
        include __DIR__ . '/tables/' . $number . ".php";
    }

    public function testHasMany()
    {
        $this->loadSeeed(1);

        $user = FixtureLoader::load(__DIR__ . '/fixtures/hasMany');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\User', $user);

        $this->assertEquals(3, $user->orders->count());

        foreach ($user->orders as $index => $order) {

            $this->assertInstanceOf('Driade\Fixtures\Test\Models\Order', $order);
            $this->assertSame($index + 1, $order->id);

            $this->assertNotEquals(0, $order->products->count());

            foreach ($order->products as $index2 => $product) {
                $this->assertInstanceOf('Driade\Fixtures\Test\Models\OrderProduct', $product);
                $this->assertSame($index * 2 + $index2 + 1, $product->id);
            };
        }
    }

    public function testBelongs()
    {
        $this->loadSeeed(1);

        $order = FixtureLoader::load(__DIR__ . '/fixtures/belongs');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Order', $order);
        $this->assertEquals(1, $order->id);
        $this->assertInstanceOf('Driade\Fixtures\Test\Models\User', $order->user);

        $this->assertEquals(1, $order->user->id);
    }

    public function testComplex()
    {
        $this->loadSeeed(1);

        $order = FixtureLoader::load(__DIR__ . '/fixtures/complex');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Order', $order);
        $this->assertEquals(1, $order->id);
        $this->assertInstanceOf('Driade\Fixtures\Test\Models\User', $order->user);

        $this->assertEquals(1, $order->user->id);

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Courier', $order->courier);
        $this->assertEquals(1, $order->courier->id);
    }
}
