<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList\Tests;

use Djdaca\SortedLinkedList\Enum\ListTypeEnum;
use Djdaca\SortedLinkedList\Internal\Node;
use Djdaca\SortedLinkedList\SortedLinkedList;
use Djdaca\SortedLinkedList\SortedLinkedListFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SortedLinkedListFactory::class)]
#[UsesClass(SortedLinkedList::class)]
#[UsesClass(Node::class)]
#[UsesClass(ListTypeEnum::class)]
final class SortedLinkedListFactoryTest extends TestCase
{
    public function testCreateReturnsEmptyList(): void
    {
        $list = SortedLinkedListFactory::create();

        self::assertInstanceOf(SortedLinkedList::class, $list);
        self::assertTrue($list->isEmpty());
        self::assertSame(0, $list->count());
    }

    public function testCreateFromEmptyArray(): void
    {
        $list = SortedLinkedListFactory::createFromArray([]);

        self::assertTrue($list->isEmpty());
        self::assertSame(0, $list->count());
    }

    public function testCreateFromIntegerArray(): void
    {
        $list = SortedLinkedListFactory::createFromArray([5, 2, 8, 1, 9]);

        self::assertSame(5, $list->count());
        self::assertSame([1, 2, 5, 8, 9], $list->toArray());
    }

    public function testCreateFromStringArray(): void
    {
        $list = SortedLinkedListFactory::createFromArray(['dog', 'apple', 'zebra', 'cat']);

        self::assertSame(4, $list->count());
        self::assertSame(['apple', 'cat', 'dog', 'zebra'], $list->toArray());
    }

    public function testCreateFromArrayWithDuplicates(): void
    {
        $list = SortedLinkedListFactory::createFromArray([5, 3, 5, 3, 5]);

        self::assertSame(5, $list->count());
        self::assertSame([3, 3, 5, 5, 5], $list->toArray());
    }

    public function testCreateFromSingleElementArray(): void
    {
        $list = SortedLinkedListFactory::createFromArray([42]);

        self::assertSame(1, $list->count());
        self::assertSame(42, $list->peek());
    }

    public function testCreateFromArrayWithNegativeNumbers(): void
    {
        $list = SortedLinkedListFactory::createFromArray([-5, 10, -3, 0, 7]);

        self::assertSame([-5, -3, 0, 7, 10], $list->toArray());
    }

    public function testCreateFromArrayMaintainsSortOrder(): void
    {
        $list = SortedLinkedListFactory::createFromArray([100, 1, 50, 25, 75]);

        self::assertSame([1, 25, 50, 75, 100], $list->toArray());
    }
}
