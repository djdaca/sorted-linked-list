<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList\Dto;

final class ListNodeDto
{
    public function __construct(
        public int | string $value,
        public null | self $previous = null,
        public null | self $next = null
    ) {
    }
}
