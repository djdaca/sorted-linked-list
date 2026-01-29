<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList\Internal;

final class Node
{
    public function __construct(
        public int | string $value,
        public null | self $previous = null,
        public null | self $next = null
    ) {
    }
}
