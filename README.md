# Sorted Linked List

[![PHP Version](https://img.shields.io/badge/php-%5E8.4-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A type-safe, automatically sorted doubly-linked list implementation for PHP 8.4+. The list maintains elements in sorted order (ascending or descending) and enforces type consistency - it can hold either integers or strings, but not both.

## Features

- ✅ **Automatic Sorting**: Elements are automatically kept in ascending or descending order
- ✅ **Type Safety**: Enforces single type (int or string) per list instance
- ✅ **Standard PHP Interfaces**: Implements `IteratorAggregate`, `Countable`, and `JsonSerializable`
- ✅ **Rich API**: Insert, peek, pop, remove, contains, and more
- ✅ **Comprehensive Tests**: 100% test coverage with PHPUnit
- ✅ **Static Analysis**: PHPStan level 9 compliant
- ✅ **Modern PHP**: Requires PHP 8.4+

## Installation

```bash
composer require djdaca/sorted-linked-list
```

For development setup and contributing, see [INSTALL.md](INSTALL.md).

## Quick Start

```php
use Djdaca\SortedLinkedList\SortedLinkedList;
use Djdaca\SortedLinkedList\Enum\SortDirectionEnum;

// Create a new list (ascending by default)
$list = new SortedLinkedList();

// Insert values - they're automatically sorted
// Fluent interface supported!
$list->insert(5)
    ->insert(2)
    ->insert(8)
    ->insert(1);

echo $list->toArray(); // [1, 2, 5, 8]

// Create a descending list
$descList = new SortedLinkedList(SortDirectionEnum::DESC);
$descList->insert(5)
    ->insert(2)
    ->insert(8);

echo $descList->toArray(); // [8, 5, 2]
```

## Usage Examples

### Basic Operations

```php
use Djdaca\SortedLinkedList\SortedLinkedList;

$list = new SortedLinkedList();

// Insert values (fluent interface)
$list->insert(10)
    ->insert(5)
    ->insert(15);

// Check count
echo $list->count(); // 3

// Peek at first element (without removing)
echo $list->peek(); // 5

// Peek at last element
echo $list->peekLast(); // 15

// Pop first element
$first = $list->pop(); // Returns 5, removes from list

// Pop last element
$last = $list->popLast(); // Returns 15, removes from list

// Check if value exists
if ($list->contains(10)) {
    echo "List contains 10";
}

// Remove specific value
$list->remove(10); // Returns true if removed, false if not found

// Check if empty
if ($list->isEmpty()) {
    echo "List is empty";
}

// Clear all elements
$list->clear();
```

### Working with Strings

```php
$list = new SortedLinkedList();

$list->insert('dog')
    ->insert('apple')
    ->insert('zebra')
    ->insert('cat');

echo $list->toArray(); // ['apple', 'cat', 'dog', 'zebra']
```

### Type Safety

```php
$list = new SortedLinkedList();
$list->insert(5);

// This will throw ListTypeMismatchException
try {
    $list->insert('string'); // ❌ Cannot mix types
} catch (ListTypeMismatchException $e) {
    echo "Cannot mix int and string!";
}

// After clear(), you can use a different type
$list->clear();
$list->insert('apple'); // ✅ Now it's a string list
```

### Sort Direction

```php
use Djdaca\SortedLinkedList\Enum\SortDirectionEnum;

// Ascending (default)
$asc = new SortedLinkedList();
$asc = new SortedLinkedList(SortDirectionEnum::ASC);
$asc->insert(5)
    ->insert(2)
    ->insert(8);
echo $asc->toArray(); // [2, 5, 8]

// Descending
$desc = new SortedLinkedList(SortDirectionEnum::DESC);
$desc->insert(5)
    ->insert(2)
    ->insert(8);
echo $desc->toArray(); // [8, 5, 2]

// Works with strings too
$descStrings = new SortedLinkedList(SortDirectionEnum::DESC);
$descStrings->insert('apple')
    ->insert('zebra')
    ->insert('cat');
echo $descStrings->toArray(); // ['zebra', 'cat', 'apple']
```

### Using Factory

```php
use Djdaca\SortedLinkedList\SortedLinkedListFactory;
use Djdaca\SortedLinkedList\Enum\SortDirectionEnum;

// Create from array (ascending by default)
$list = SortedLinkedListFactory::createFromArray([5, 2, 8, 1, 9]);
echo $list->toArray(); // [1, 2, 5, 8, 9]

// Create from array with descending order
$descList = SortedLinkedListFactory::createFromArray([5, 2, 8, 1, 9], SortDirectionEnum::DESC);
echo $descList->toArray(); // [9, 8, 5, 2, 1]

// Create empty list
$list = SortedLinkedListFactory::create();
$descList = SortedLinkedListFactory::create(SortDirectionEnum::DESC);
```

### Iteration

```php
$list = new SortedLinkedList();
$list->insert(5)
    ->insert(2)
    ->insert(8);

// Using foreach
foreach ($list as $value) {
    echo $value . "\n";
}
// Output: 2, 5, 8

// Using iterator_to_array
$array = iterator_to_array($list);
```

### JSON Serialization

```php
$list = new SortedLinkedList();
$list->insert(5)
    ->insert(2)
    ->insert(8);

$json = json_encode($list);
// {"type":"int","count":3,"values":[2,5,8]}
```

### Handling Duplicates

```php
$list = new SortedLinkedList();
$list->insert(5)
    ->insert(3)
    ->insert(5)
    ->insert(3);

echo $list->toArray(); // [3, 3, 5, 5]

// Remove only removes first occurrence
$list->remove(5); // Removes one 5
echo $list->toArray(); // [3, 3, 5]
```

## API Reference

### Methods

#### `insert(int|string $value): self`
Inserts a value into the list in sorted order. Returns `$this` for method chaining.
- Throws `ListTypeMismatchException` if type doesn't match existing values
- Example: `$list->insert(5)->insert(2)->insert(8);`

#### `peek(): int|string`
Returns the first (smallest) element without removing it.
- Throws `ListEmptyException` if list is empty

#### `peekLast(): int|string`
Returns the last (largest) element without removing it.
- Throws `ListEmptyException` if list is empty

#### `pop(): int|string`
Removes and returns the first (smallest) element.
- Throws `ListEmptyException` if list is empty

#### `popLast(): int|string`
Removes and returns the last (largest) element.
- Throws `ListEmptyException` if list is empty

#### `contains(int|string $value): bool`
Checks if a value exists in the list.

#### `remove(int|string $value): bool`
Removes the first occurrence of a value. Returns `true` if removed, `false` if not found.

#### `toArray(): array`
Returns all values as an array in sorted order.

#### `count(): int`
Returns the number of elements in the list.

#### `isEmpty(): bool`
Checks if the list is empty.

#### `getType(): ListTypeEnum`
Returns the type of values stored (INT or STRING).
- Throws `ListEmptyException` if list is empty

#### `clear(): void`
Removes all elements from the list. After clearing, a different type can be inserted.

#### `getIterator(): Traversable`
Returns an iterator for the list (implements `IteratorAggregate`).

#### `jsonSerialize(): array`
Returns a JSON-serializable representation (implements `JsonSerializable`).

## Exceptions

- `ListEmptyException` - Thrown when attempting operations on an empty list
- `ListTypeMismatchException` - Thrown when attempting to insert a value of different type

## Development

For development setup, Docker environment, testing, and contribution guidelines, see [INSTALL.md](INSTALL.md).

### Quick Commands

```bash
# Start development environment
make start

# Run tests
make test

# Run all checks
make ci
```

## License

MIT License - see LICENSE file for details

## Contributing

Contributions are welcome! Please see [INSTALL.md](INSTALL.md) for development setup and guidelines.

**Requirements:**
- All tests must pass
- PHPStan level 9 compliant
- Code follows PSR-12 style guide
- New features include tests

## Author

DJDaca - djdaca@gmail.com
