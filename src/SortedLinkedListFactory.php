<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList;

final class SortedLinkedListFactory
{
    public static function create(): SortedLinkedList
    {
        return new SortedLinkedList();
    }

    /**
     * @param list<int|string> $values
     */
    public static function createFromArray(array $values): SortedLinkedList
    {
        $list = new SortedLinkedList();
        foreach ($values as $value) {
            $list->insert($value);
        }

        return $list;
    }
}
