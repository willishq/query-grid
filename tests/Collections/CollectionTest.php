<?php

namespace Tests;

use Willishq\QueryGrid\Collections\Collection;

class CollectionTest extends TestCase
{
    /** @test */
    public function itCanCreateAnAccessibleCollection()
    {
        $collection = new Collection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);

        $this->assertTrue(isset($collection[0]));
        $this->assertTrue(isset($collection[1]));
        $this->assertTrue(isset($collection[2]));
        $this->assertFalse(isset($collection[3]));
        $this->assertEquals(1, $collection[0]);
        $this->assertEquals(2, $collection[1]);
        $this->assertEquals(3, $collection[2]);
    }

    /**
     * @test
     * @expectedException \Willishq\QueryGrid\Collections\CollectionException
     * @expectedExceptionMessage You can not change a collection value using that method.
     */
    public function itCanNotSetOnACollection()
    {
        $collection = new Collection();
        $collection[0] = 1;
    }

    /**
     * @test
     * @expectedException \Willishq\QueryGrid\Collections\CollectionException
     * @expectedExceptionMessage You can not unset a collection value using that method.
     */
    public function itCanNotUnsetOnACollection()
    {
        $collection = new Collection();
        unset($collection[0]);
    }


    /** @test */
    public function itCreatesACountableCollection()
    {
        $collection = new Collection();
        $this->assertCount(0, $collection);
        $collection->add(1);
        $this->assertCount(1, $collection);
        $collection->add(1);
        $this->assertCount(2, $collection);
    }

    /** @test */
    public function itCanGetAnOffset()
    {
        $collection = new Collection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);

        $this->assertEquals(1, $collection->get(0));
        $this->assertEquals(2, $collection->get(1));
        $this->assertEquals(3, $collection->get(2));
    }

    /** @test */
    public function itCanGetTheFirstElement()
    {
        $collection = new Collection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);

        $this->assertEquals(1, $collection->first());
    }

    /** @test */
    public function itCanGetTheLastElement()
    {
        $collection = new Collection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);

        $this->assertEquals(3, $collection->last());
    }

    /** @test */
    public function itCanReturnANewCollectionOfMappedItems()
    {
        $collection = new Collection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);

        $mapped = $collection->map(function ($i) {
            return $i * 2;
        });

        $this->assertEquals(1, $collection->get(0));
        $this->assertEquals(2, $collection->get(1));
        $this->assertEquals(3, $collection->get(2));
        $this->assertEquals(2, $mapped->get(0));
        $this->assertEquals(4, $mapped->get(1));
        $this->assertEquals(6, $mapped->get(2));
    }

    /** @test */
    public function itCanReturnAllItemsAsAnArray()
    {
        $collection = new Collection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);

        $this->assertEquals([1, 2, 3], $collection->all());
    }

    /** @test */
    public function itCanFilterArrayValuesAndReturnANewCollection()
    {
        $collection = new Collection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);

        $filtered = $collection->filter(function ($i) {
            return $i > 1;
        });

        $this->assertCount(3, $collection);
        $this->assertCount(2, $filtered);
        $this->assertEquals(1, $collection->get(0));
        $this->assertEquals(2, $collection->get(1));
        $this->assertEquals(3, $collection->get(2));
        $this->assertEquals(2, $filtered->get(1));
        $this->assertEquals(3, $filtered->get(2));
    }

    /** @test */
    public function itCanKeyResultsByCallable()
    {
        $collection = new Collection();
        $collection->add(['k' => 'one', 'value' => 1]);
        $collection->add(['k' => 'two', 'value' => 2]);
        $collection->add(['k' => 'three', 'value' => 3]);

        $keyed = $collection->keyBy(function ($item) {
            return $item['k'];
        });

        $this->assertArrayHasKey('one', $keyed);
        $this->assertArrayHasKey('two', $keyed);
        $this->assertArrayHasKey('three', $keyed);
        $this->assertEquals(['k' => 'one', 'value' => 1], $keyed['one']);
        $this->assertEquals(['k' => 'two', 'value' => 2], $keyed['two']);
        $this->assertEquals(['k' => 'three', 'value' => 3], $keyed['three']);
    }
    /** @test */
    public function itCanKeyResultsAndValuesByCallable()
    {
        $collection = new Collection();
        $collection->add(['k' => 'one', 'value' => 1]);
        $collection->add(['k' => 'two', 'value' => 2]);
        $collection->add(['k' => 'three', 'value' => 3]);

        $keyed = $collection->keyBy(function ($item) {
            return $item['k'];
        }, function ($item) {
            return $item['value'];
        });

        $this->assertArrayHasKey('one', $keyed);
        $this->assertArrayHasKey('two', $keyed);
        $this->assertArrayHasKey('three', $keyed);
        $this->assertEquals(1, $keyed['one']);
        $this->assertEquals(2, $keyed['two']);
        $this->assertEquals(3, $keyed['three']);
    }
}