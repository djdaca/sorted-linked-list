<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList;

use Djdaca\SortedLinkedList\Enum\SortDirectionEnum;

final class SortedLinkedListFactory
{
    public static function create(SortDirectionEnum $sortDirection = SortDirectionEnum::ASC): SortedLinkedList
    {
        return new SortedLinkedList($sortDirection);
    }

    /**
     * @param list<int|string> $values
     */
    public static function createFromArray(array $values, SortDirectionEnum $sortDirection = SortDirectionEnum::ASC): SortedLinkedList
    {
        $list = new SortedLinkedList($sortDirection);
        foreach ($values as $value) {
            $list->insert($value);
        }

        return $list;
    }
}
