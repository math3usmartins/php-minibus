<?php

namespace MiniBus\Test\Envelope\Stamp;

use MiniBus\Envelope\Stamp;
use MiniBus\Envelope\Stamp\StampCollection;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Envelope\Stamp\StampCollection
 */
final class StampCollectionTest extends TestCase
{
    /**
     * @param bool $expected
     * @dataProvider has_given_stamp_scenarios
     */
    public function testHasGivenStamp(
        StampCollection $collection,
        Stamp $stamp,
        $expected
    ) {
        static::assertEquals($expected, $collection->contains($stamp));
    }

    public function has_given_stamp_scenarios()
    {
        $fooStampName = 'foo';
        $fooStampValue = 'bar';
        $fooStamp = new StubStamp($fooStampName, $fooStampValue);

        yield 'empty collection' => [
            'collection' => new StampCollection(),
            'stamp' => $fooStamp,
            'expected' => false,
        ];

        $anotherStamp = new StubStamp($fooStampName, 'another-value');

        yield 'non empty collection without matching stamps' => [
            'collection' => new StampCollection($fooStamp),
            'stamp' => $anotherStamp,
            'expected' => false,
        ];

        yield 'collection with matching stamp' => [
            'collection' => new StampCollection($fooStamp),
            'stamp' => $fooStamp,
            'expected' => true,
        ];
    }

    /**
     * @param string          $name
     * @param StampCollection $expected
     * @dataProvider find_all_stamps_scenarios
     */
    public function testFindAllStamps(
        StampCollection $collection,
        $name,
        $expected
    ) {
        static::assertEquals($expected, $collection->all($name));
    }

    public function find_all_stamps_scenarios()
    {
        $fooStampName = 'foo';
        $fooStampValue = 'bar';
        $fooStamp = new StubStamp($fooStampName, $fooStampValue);

        yield 'empty collection' => [
            'collection' => new StampCollection(),
            'name' => $fooStampName,
            'expected' => new StampCollection(),
        ];

        yield 'non empty collection without matching stamps' => [
            'collection' => new StampCollection($fooStamp),
            'name' => 'another name',
            'expected' => new StampCollection(),
        ];

        yield 'collection with matching stamp' => [
            'collection' => new StampCollection($fooStamp),
            'name' => $fooStampName,
            'expected' => new StampCollection($fooStamp),
        ];

        $anotherFooStamp = new StubStamp($fooStampName, 'another-value');

        yield 'collection with multiple matches' => [
            'collection' => new StampCollection($fooStamp, $anotherFooStamp),
            'name' => $fooStampName,
            'expected' => new StampCollection($fooStamp, $anotherFooStamp),
        ];
    }

    /**
     * @param string     $name
     * @param Stamp|null $expected
     * @dataProvider find_last_stamp_scenarios
     */
    public function testFindLastStamp(
        StampCollection $collection,
        $name,
        $expected
    ) {
        static::assertEquals($expected, $collection->last($name));
    }

    public function find_last_stamp_scenarios()
    {
        $fooStampName = 'foo';
        $fooStampValue = 'bar';
        $fooStamp = new StubStamp($fooStampName, $fooStampValue);

        yield 'empty collection' => [
            'collection' => new StampCollection(),
            'name' => $fooStampName,
            'expected' => null,
        ];

        yield 'non empty collection without matching stamps' => [
            'collection' => new StampCollection($fooStamp),
            'name' => 'another name',
            'expected' => null,
        ];

        yield 'collection with matching stamp' => [
            'collection' => new StampCollection($fooStamp),
            'name' => $fooStampName,
            'expected' => $fooStamp,
        ];

        $anotherFooStamp = new StubStamp($fooStampName, 'another-value');

        yield 'collection with multiple matches' => [
            'collection' => new StampCollection($fooStamp, $anotherFooStamp),
            'name' => $fooStampName,
            'expected' => $anotherFooStamp,
        ];
    }

    /**
     * @param int $expectedCount
     * @dataProvider countScenarios
     */
    public function testCount(StampCollection $collection, $expectedCount)
    {
        static::assertEquals($expectedCount, $collection->count());
    }

    public function countScenarios()
    {
        yield 'no stamps' => [
            'collection' => new StampCollection(),
            'expected count' => 0,
        ];

        $fooStampName = 'foo';
        $fooStampValue = 'bar';
        $fooStamp = new StubStamp($fooStampName, $fooStampValue);

        yield 'single stamp' => [
            'collection' => new StampCollection($fooStamp),
            'expected count' => 1,
        ];

        yield 'multiple stamps' => [
            'collection' => new StampCollection($fooStamp, $fooStamp),
            'expected count' => 2,
        ];
    }

    /**
     * @dataProvider withStampScenarios
     */
    public function testWithStamp(
        StampCollection $collection,
        Stamp $stamp,
        StampCollection $expected
    ) {
        static::assertEquals($expected, $collection->with($stamp));
    }

    public function withStampScenarios()
    {
        $fooStampName = 'foo';
        $fooStampValue = 'bar';
        $fooStamp = new StubStamp($fooStampName, $fooStampValue);

        yield 'add to empty collection' => [
            'collection' => new StampCollection(),
            'stamp' => $fooStamp,
            'expected' => new StampCollection($fooStamp),
        ];

        $anotherFooStamp = new StubStamp($fooStampName, 'another-value');

        yield 'append another stamp with same name' => [
            'collection' => new StampCollection($fooStamp),
            'stamp' => $anotherFooStamp,
            'expected' => new StampCollection($fooStamp, $anotherFooStamp),
        ];

        $barStamp = new StubStamp('bar', 'another-value');

        // collection is not empty, but there are no other stamps matching
        // the given stamp name.
        yield 'add stamp with non existing name' => [
            'collection' => new StampCollection($fooStamp),
            'stamp' => $barStamp,
            'expected' => new StampCollection($fooStamp, $barStamp),
        ];
    }
}
