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
        $this->loadSeed(1);

        $user = FixtureLoader::load(__DIR__ . '/fixtures/hasMany.php');

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
        $this->loadSeed(1);

        $order = FixtureLoader::load(__DIR__ . '/fixtures/belongs.php');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Order', $order);
        $this->assertEquals(1, $order->id);
        $this->assertInstanceOf('Driade\Fixtures\Test\Models\User', $order->user);

        $this->assertEquals(1, $order->user->id);
    }

    public function testComplex()
    {
        $this->loadSeed(1);

        $order = FixtureLoader::load(__DIR__ . '/fixtures/complex.php');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Order', $order);
        $this->assertEquals(1, $order->id);
        $this->assertInstanceOf('Driade\Fixtures\Test\Models\User', $order->user);

        $this->assertEquals(1, $order->user->id);

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Courier', $order->courier);
        $this->assertEquals(1, $order->courier->id);
    }

    public function testHasOne()
    {
        $this->loadSeed(2);

        $owner = FixtureLoader::load(__DIR__ . '/fixtures/hasOne.php');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Owner', $owner);
        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Dog', $owner->dog);
    }

    public function testHasOneInverse()
    {
        $this->loadSeed(2);

        $dog = FixtureLoader::load(__DIR__ . '/fixtures/hasOne2');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Dog', $dog);
        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Owner', $dog->owner);
    }

    public function testBelongsToMany()
    {
        $this->loadSeed(3);

        $author = FixtureLoader::load(__DIR__ . '/fixtures/belongsToMany.php');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Author', $author);

        $this->assertEquals(2, $author->books->count());

        foreach ($author->books as $index => $book) {
            $this->assertInstanceOf('Driade\Fixtures\Test\Models\Book', $book);
            $this->assertEquals($index + 1, $book->id);
        }
    }

    public function testPolymorphic()
    {
        $this->loadSeed(4);

        $photo = FixtureLoader::load(__DIR__ . '/fixtures/polymorphic.php');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Photo', $photo);

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Staff', $photo->imageable);
    }

    public function testPolymorphic2()
    {
        $this->loadSeed(4);

        $staff = FixtureLoader::load(__DIR__ . '/fixtures/polymorphic2.php');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Staff', $staff);

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Photo', $staff->photos->first());
    }

    public function testClassConstant()
    {
        $this->loadSeed(1);

        if (PHP_MAJOR_VERSION < 5 && PHP_MINOR_VERSION < 4) {
            $this->markTestSkipped(
                'Run in PHP >= 5.4.'
            );
        }

        $user = FixtureLoader::load(__DIR__ . '/fixtures/classConstant.php');

        $this->assertInstanceOf('Driade\Fixtures\Test\Models\User', $user);
        $this->assertInstanceOf('Driade\Fixtures\Test\Models\Order', $user->orders->first());
    }
}
