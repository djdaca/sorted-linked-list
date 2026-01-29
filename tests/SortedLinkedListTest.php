<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList\Tests;

use Djdaca\SortedLinkedList\Enum\ListTypeEnum;
use Djdaca\SortedLinkedList\Exception\ListEmptyException;
use Djdaca\SortedLinkedList\Exception\ListTypeMismatchException;
use Djdaca\SortedLinkedList\Internal\Node;
use Djdaca\SortedLinkedList\SortedLinkedList;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SortedLinkedList::class)]
#[UsesClass(Node::class)]
#[UsesClass(ListTypeEnum::class)]
#[UsesClass(ListEmptyException::class)]
#[UsesClass(ListTypeMismatchException::class)]
final class SortedLinkedListTest extends TestCase
{
    public function testInsertSingleIntegerValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        self::assertSame(1, $list->count());
        self::assertSame(5, $list->peek());
        self::assertSame(ListTypeEnum::INT, $list->getType());
    }

    public function testInsertSingleStringValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert('apple');

        self::assertSame(1, $list->count());
        self::assertSame('apple', $list->peek());
        self::assertSame(ListTypeEnum::STRING, $list->getType());
    }

    public function testInsertMultipleIntegersInOrder(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);
        $list->insert(1);
        $list->insert(9);

        self::assertSame(5, $list->count());
        self::assertSame([1, 2, 5, 8, 9], $list->toArray());
    }

    public function testInsertMultipleStringsInOrder(): void
    {
        $list = new SortedLinkedList();
        $list->insert('dog');
        $list->insert('apple');
        $list->insert('zebra');
        $list->insert('cat');

        self::assertSame(4, $list->count());
        self::assertSame(['apple', 'cat', 'dog', 'zebra'], $list->toArray());
    }

    public function testInsertDuplicateValues(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(3);
        $list->insert(5);
        $list->insert(3);
        $list->insert(5);

        self::assertSame(5, $list->count());
        self::assertSame([3, 3, 5, 5, 5], $list->toArray());
    }

    public function testInsertTypeMismatchThrowsException(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        $this->expectException(ListTypeMismatchException::class);
        $list->insert('string');
    }

    public function testInsertStringAfterIntThrowsException(): void
    {
        $list = new SortedLinkedList();
        $list->insert('apple');

        $this->expectException(ListTypeMismatchException::class);
        $list->insert(10);
    }

    public function testPeekReturnsFirstElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(5);
        $list->insert(15);

        self::assertSame(5, $list->peek());
        self::assertSame(3, $list->count()); // Peek should not modify the list
    }

    public function testPeekOnEmptyListThrowsException(): void
    {
        $list = new SortedLinkedList();

        $this->expectException(ListEmptyException::class);
        $list->peek();
    }

    public function testPeekLastReturnsLastElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(5);
        $list->insert(15);

        self::assertSame(15, $list->peekLast());
        self::assertSame(3, $list->count()); // PeekLast should not modify the list
    }

    public function testPeekLastOnEmptyListThrowsException(): void
    {
        $list = new SortedLinkedList();

        $this->expectException(ListEmptyException::class);
        $list->peekLast();
    }

    public function testPopRemovesAndReturnsFirstElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(5);
        $list->insert(15);

        $value = $list->pop();

        self::assertSame(5, $value);
        self::assertSame(2, $list->count());
        self::assertSame([10, 15], $list->toArray());
    }

    public function testPopMultipleTimes(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        self::assertSame(1, $list->pop());
        self::assertSame(2, $list->pop());
        self::assertSame(3, $list->pop());
        self::assertTrue($list->isEmpty());
    }

    public function testPopOnEmptyListThrowsException(): void
    {
        $list = new SortedLinkedList();

        $this->expectException(ListEmptyException::class);
        $list->pop();
    }

    public function testPopUntilEmpty(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->pop();

        self::assertTrue($list->isEmpty());
        $this->expectException(ListEmptyException::class);
        $list->pop();
    }

    public function testPopLastRemovesAndReturnsLastElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(5);
        $list->insert(15);

        $value = $list->popLast();

        self::assertSame(15, $value);
        self::assertSame(2, $list->count());
        self::assertSame([5, 10], $list->toArray());
    }

    public function testPopLastMultipleTimes(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        self::assertSame(3, $list->popLast());
        self::assertSame(2, $list->popLast());
        self::assertSame(1, $list->popLast());
        self::assertTrue($list->isEmpty());
    }

    public function testPopLastOnEmptyListThrowsException(): void
    {
        $list = new SortedLinkedList();

        $this->expectException(ListEmptyException::class);
        $list->popLast();
    }

    public function testPopLastOnSingleElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(42);

        self::assertSame(42, $list->popLast());
        self::assertTrue($list->isEmpty());
    }

    public function testClearEmptiesList(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(15);

        $list->clear();

        self::assertTrue($list->isEmpty());
        self::assertSame(0, $list->count());
        self::assertSame([], $list->toArray());
    }

    public function testClearAllowsNewType(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);

        $list->clear();

        // After clear, can insert different type
        $list->insert('apple');
        $list->insert('banana');

        self::assertSame(['apple', 'banana'], $list->toArray());
        self::assertSame(ListTypeEnum::STRING, $list->getType());
    }

    public function testClearOnEmptyList(): void
    {
        $list = new SortedLinkedList();

        $list->clear();

        self::assertTrue($list->isEmpty());
    }

    public function testContainsReturnsTrueForExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(3);

        self::assertTrue($list->contains(5));
        self::assertTrue($list->contains(10));
        self::assertTrue($list->contains(3));
    }

    public function testContainsReturnsFalseForNonExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);

        self::assertFalse($list->contains(3));
        self::assertFalse($list->contains(15));
    }

    public function testContainsOnEmptyList(): void
    {
        $list = new SortedLinkedList();

        self::assertFalse($list->contains(5));
    }

    public function testRemoveExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(3);

        $result = $list->remove(10);

        self::assertTrue($result);
        self::assertSame(2, $list->count());
        self::assertSame([3, 5], $list->toArray());
        self::assertFalse($list->contains(10));
    }

    public function testRemoveFirstElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(3);

        $result = $list->remove(3);

        self::assertTrue($result);
        self::assertSame([5, 10], $list->toArray());
    }

    public function testRemoveLastElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(3);

        $result = $list->remove(10);

        self::assertTrue($result);
        self::assertSame([3, 5], $list->toArray());
    }

    public function testRemoveMiddleElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(3);

        $result = $list->remove(5);

        self::assertTrue($result);
        self::assertSame([3, 10], $list->toArray());
    }

    public function testRemoveNonExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);

        $result = $list->remove(15);

        self::assertFalse($result);
        self::assertSame(2, $list->count());
    }

    public function testRemoveFromEmptyList(): void
    {
        $list = new SortedLinkedList();

        $result = $list->remove(5);

        self::assertFalse($result);
    }

    public function testRemoveOnlyElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        $result = $list->remove(5);

        self::assertTrue($result);
        self::assertTrue($list->isEmpty());
    }

    public function testRemoveDuplicateOnlyRemovesOne(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(5);
        $list->insert(5);

        $result = $list->remove(5);

        self::assertTrue($result);
        self::assertSame(2, $list->count());
        self::assertSame([5, 5], $list->toArray());
    }

    public function testToArrayReturnsEmptyArrayForEmptyList(): void
    {
        $list = new SortedLinkedList();

        self::assertSame([], $list->toArray());
    }

    public function testCountReturnsZeroForEmptyList(): void
    {
        $list = new SortedLinkedList();

        self::assertSame(0, $list->count());
    }

    public function testCountReturnsCorrectValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(3);

        self::assertSame(3, $list->count());
    }

    public function testIsEmptyReturnsTrueForEmptyList(): void
    {
        $list = new SortedLinkedList();

        self::assertTrue($list->isEmpty());
    }

    public function testIsEmptyReturnsFalseForNonEmptyList(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        self::assertFalse($list->isEmpty());
    }

    public function testGetIterator(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $values = [];
        foreach ($list as $value) {
            $values[] = $value;
        }

        self::assertSame([2, 5, 8], $values);
    }

    public function testGetIteratorOnEmptyList(): void
    {
        $list = new SortedLinkedList();

        $values = [];
        foreach ($list as $value) {
            $values[] = $value;
        }

        self::assertSame([], $values);
    }

    public function testJsonSerializeWithIntegers(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $json = $list->jsonSerialize();

        self::assertSame([
            'type' => 'int',
            'count' => 3,
            'values' => [2, 5, 8],
        ], $json);
    }

    public function testJsonSerializeWithStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert('dog');
        $list->insert('apple');
        $list->insert('cat');

        $json = $list->jsonSerialize();

        self::assertSame([
            'type' => 'string',
            'count' => 3,
            'values' => ['apple', 'cat', 'dog'],
        ], $json);
    }

    public function testJsonSerializeOnEmptyListThrowsException(): void
    {
        $list = new SortedLinkedList();

        $this->expectException(ListEmptyException::class);
        $list->jsonSerialize();
    }

    public function testGetTypeOnEmptyListThrowsException(): void
    {
        $list = new SortedLinkedList();

        $this->expectException(ListEmptyException::class);
        $list->getType();
    }

    public function testComplexScenario(): void
    {
        $list = new SortedLinkedList();

        // Insert multiple values
        $list->insert(50);
        $list->insert(20);
        $list->insert(80);
        $list->insert(10);
        $list->insert(60);

        self::assertSame([10, 20, 50, 60, 80], $list->toArray());

        // Remove some values
        $list->remove(20);
        $list->remove(80);

        self::assertSame([10, 50, 60], $list->toArray());

        // Pop first value
        self::assertSame(10, $list->pop());

        // Insert more values
        $list->insert(30);
        $list->insert(70);

        self::assertSame([30, 50, 60, 70], $list->toArray());

        // Check contains
        self::assertTrue($list->contains(50));
        self::assertFalse($list->contains(10));

        self::assertSame(4, $list->count());
    }

    public function testInsertAtBeginning(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(20);
        $list->insert(5); // Should be inserted at the beginning

        self::assertSame([5, 10, 20], $list->toArray());
    }

    public function testInsertAtEnd(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(10);
        $list->insert(20); // Should be inserted at the end

        self::assertSame([5, 10, 20], $list->toArray());
    }

    public function testInsertInMiddle(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(20);
        $list->insert(10); // Should be inserted in the middle

        self::assertSame([5, 10, 20], $list->toArray());
    }

    public function testLargeNumberOfElements(): void
    {
        $list = new SortedLinkedList();
        $values = range(1, 100);
        shuffle($values);

        foreach ($values as $value) {
            $list->insert($value);
        }

        self::assertSame(100, $list->count());
        self::assertSame(range(1, 100), $list->toArray());
    }

    public function testStringOrdering(): void
    {
        $list = new SortedLinkedList();
        $strings = ['zebra', 'apple', 'banana', 'mango', 'cherry'];

        foreach ($strings as $string) {
            $list->insert($string);
        }

        self::assertSame(['apple', 'banana', 'cherry', 'mango', 'zebra'], $list->toArray());
    }

    /**
     * @param list<int> $input
     * @param list<int> $expected
     */
    #[DataProvider('provideNegativeNumbers')]
    public function testNegativeNumbers(array $input, array $expected): void
    {
        $list = new SortedLinkedList();

        foreach ($input as $value) {
            $list->insert($value);
        }

        self::assertSame($expected, $list->toArray());
    }

    /**
     * @return array<string, array{input: list<int>, expected: list<int>}>
     */
    public static function provideNegativeNumbers(): array
    {
        return [
            'mixed positive and negative' => [
                'input' => [5, -3, 10, -8, 0, 2],
                'expected' => [-8, -3, 0, 2, 5, 10],
            ],
            'all negative' => [
                'input' => [-5, -2, -10, -1],
                'expected' => [-10, -5, -2, -1],
            ],
            'with zero' => [
                'input' => [0, -5, 5, 0, -10, 10],
                'expected' => [-10, -5, 0, 0, 5, 10],
            ],
        ];
    }
}
