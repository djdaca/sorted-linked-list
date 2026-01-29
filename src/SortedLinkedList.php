<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList;

use Countable;
use Djdaca\SortedLinkedList\Dto\ListNodeDto;
use Djdaca\SortedLinkedList\Enum\ListTypeEnum;
use Djdaca\SortedLinkedList\Exception\ListEmptyException;
use Djdaca\SortedLinkedList\Exception\ListTypeMismatchException;
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

    private null | ListNodeDto $head = null;
    private null | ListNodeDto $tail = null;

    public function insert(int | string $value): void
    {
        if ($this->type === null) {
            $this->type = \is_int($value)
                ? ListTypeEnum::INT
                : ListTypeEnum::STRING;
        }

        if (\is_int($value) !== ($this->type === ListTypeEnum::INT)) {
            throw new ListTypeMismatchException(
                'List is ' . $this->type->value . ', expected ' .
                gettype($value)
            );
        }

        $newNode = new ListNodeDto($value);

        if ($this->head === null) {
            $this->head = $this->tail = $newNode;
        } else {
            $current = $this->head;
            if ($value < $current->value) {
                $newNode->next = $this->head;
                $this->head->previous = $newNode;
                $this->head = $newNode;
            } else {
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
        }

        $this->count++;
    }

    public function peek(): int | string
    {
        if ($this->head === null) {
            throw new ListEmptyException('List is empty');
        }

        return $this->head->value;
    }

    public function peekLast(): int | string
    {
        if ($this->tail === null) {
            throw new ListEmptyException('List is empty');
        }

        return $this->tail->value;
    }

    public function pop(): int | string
    {
        if ($this->head === null) {
            throw new ListEmptyException('List is empty');
        }

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
        if ($this->tail === null) {
            throw new ListEmptyException('List is empty');
        }

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
        if ($this->type === null) {
            throw new ListEmptyException('List is empty, type not determined');
        }

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
        if ($this->type === null) {
            throw new ListEmptyException('List is empty, cannot serialize');
        }

        return [
            'type' => $this->type->value,
            'count' => $this->count,
            'values' => $this->toArray(),
        ];
    }

    private function findNode(int | string $value): null | ListNodeDto
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
