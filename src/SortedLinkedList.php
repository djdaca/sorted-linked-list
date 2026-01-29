<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList;

use Countable;
use Djdaca\SortedLinkedList\Enum\ListTypeEnum;
use Djdaca\SortedLinkedList\Exception\ListEmptyException;
use Djdaca\SortedLinkedList\Exception\ListTypeMismatchException;
use Djdaca\SortedLinkedList\Internal\Node;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @implements IteratorAggregate<int|string, int|string>
 */
final class SortedLinkedList implements IteratorAggregate, Countable, JsonSerializable
{
    /** @readonly */
    private null | ListTypeEnum $type = null;

    /** @readonly */
    private int $count = 0;

    private null | Node $head = null;
    private null | Node $tail = null;

    public function insert(int | string $value): void
    {
        $this->ensureTypeIsSet($value);
        $this->validateType($value);

        $newNode = new Node($value);

        if ($this->head === null) {
            $this->insertIntoEmptyList($newNode);
        } elseif ($value < $this->head->value) {
            $this->insertAtBeginning($newNode);
        } else {
            $this->insertInSortedPosition($newNode, $value);
        }

        $this->count++;
    }

    public function peek(): int | string
    {
        $this->ensureNotEmpty();

        return $this->head->value;
    }

    public function peekLast(): int | string
    {
        $this->ensureNotEmpty();

        return $this->tail->value;
    }

    public function pop(): int | string
    {
        $this->ensureNotEmpty();

        $value = $this->head->value;
        $this->head = $this->head->next;
        if ($this->head !== null) {
            $this->head->previous = null;
        } else {
            $this->tail = null;
        }
        $this->count--;

        return $value;
    }

    public function popLast(): int | string
    {
        $this->ensureNotEmpty();

        $value = $this->tail->value;
        $this->tail = $this->tail->previous;
        if ($this->tail !== null) {
            $this->tail->next = null;
        } else {
            $this->head = null;
        }
        $this->count--;

        return $value;
    }

    public function clear(): void
    {
        $this->head = null;
        $this->tail = null;
        $this->count = 0;
        $this->type = null;
    }

    public function contains(int | string $value): bool
    {
        return $this->findNode($value) !== null;
    }

    public function remove(int | string $value): bool
    {
        $node = $this->findNode($value);
        if ($node === null) {
            return false;
        }

        $this->unlinkNode($node);
        $this->count--;

        return true;
    }

    /** @return list<int | string> */
    public function toArray(): array
    {
        $values = [];
        $current = $this->head;
        while ($current !== null) {
            $values[] = $current->value;
            $current = $current->next;
        }

        return $values;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function isEmpty(): bool
    {
        return $this->count === 0;
    }

    public function getType(): ListTypeEnum
    {
        $this->ensureNotEmpty();

        return $this->type;
    }

    public function getIterator(): Traversable
    {
        $current = $this->head;
        while ($current !== null) {
            yield $current->value;
            $current = $current->next;
        }
    }

    /**
     * @return array{type: string, count: int, values: list<int|string>}
     */
    public function jsonSerialize(): array
    {
        $this->ensureNotEmpty();

        return [
            'type' => $this->type->value,
            'count' => $this->count,
            'values' => $this->toArray(),
        ];
    }

    private function ensureTypeIsSet(int | string $value): void
    {
        if ($this->type === null) {
            $this->type = \is_int($value)
                ? ListTypeEnum::INT
                : ListTypeEnum::STRING;
        }
    }

    private function validateType(int | string $value): void
    {
        assert($this->type !== null);

        if (\is_int($value) !== ($this->type === ListTypeEnum::INT)) {
            throw new ListTypeMismatchException(
                'List is ' . $this->type->value . ', expected ' .
                gettype($value)
            );
        }
    }

    private function insertIntoEmptyList(Node $node): void
    {
        $this->head = $this->tail = $node;
    }

    private function insertAtBeginning(Node $node): void
    {
        assert($this->head !== null);

        $node->next = $this->head;
        $this->head->previous = $node;
        $this->head = $node;
    }

    private function insertInSortedPosition(Node $newNode, int | string $value): void
    {
        assert($this->head !== null);

        $current = $this->head;

        while ($current->next !== null && $current->next->value <= $value) {
            $current = $current->next;
        }

        $newNode->next = $current->next;
        $newNode->previous = $current;

        if ($current->next !== null) {
            $current->next->previous = $newNode;
        } else {
            $this->tail = $newNode;
        }

        $current->next = $newNode;
    }

    private function unlinkNode(Node $node): void
    {
        if ($node->previous !== null) {
            $node->previous->next = $node->next;
        } else {
            $this->head = $node->next;
        }

        if ($node->next !== null) {
            $node->next->previous = $node->previous;
        } else {
            $this->tail = $node->previous;
        }
    }

    /**
     * @phpstan-assert !null $this->head
     * @phpstan-assert !null $this->tail
     * @phpstan-assert !null $this->type
     */
    private function ensureNotEmpty(): void
    {
        if ($this->head === null || $this->tail === null || $this->type === null) {
            throw new ListEmptyException('List is empty');
        }
    }

    private function findNode(int | string $value): null | Node
    {
        $current = $this->head;
        while ($current !== null) {
            if ($current->value === $value) {
                return $current;
            }
            $current = $current->next;
        }

        return null;
    }
}
