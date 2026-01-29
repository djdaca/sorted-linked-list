<?php

declare(strict_types=1);

namespace Djdaca\SortedLinkedList\Tests\Internal;

use Djdaca\SortedLinkedList\Internal\Node;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Node::class)]
final class NodeTest extends TestCase
{
    public function testConstructorWithValue(): void
    {
        $node = new Node(42);

        self::assertSame(42, $node->value);
        self::assertNull($node->previous);
        self::assertNull($node->next);
    }

    public function testConstructorWithAllParameters(): void
    {
        $previous = new Node(1);
        $next = new Node(3);
        $node = new Node(2, $previous, $next);

        self::assertSame(2, $node->value);
        self::assertSame($previous, $node->previous);
        self::assertSame($next, $node->next);
    }

    public function testConstructorWithStringValue(): void
    {
        $node = new Node('test');

        self::assertSame('test', $node->value);
    }

    public function testNodeLinking(): void
    {
        $node1 = new Node(1);
        $node2 = new Node(2);
        $node3 = new Node(3);

        $node2->previous = $node1;
        $node2->next = $node3;
        $node1->next = $node2;
        $node3->previous = $node2;

        self::assertSame($node2, $node1->next);
        self::assertSame($node2, $node3->previous);
        self::assertSame($node1, $node2->previous);
        self::assertSame($node3, $node2->next);
    }
}
