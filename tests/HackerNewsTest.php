<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class HackerNewsTest extends TestCase
{
    public function testStoryCommentsConvertedIntoArray(): void
    {
        $client = new TestClient();
        $story = $client->getItem(2007);
        $have = (new HackerNews($client))->comments($story);
        $want = [
            [5143, 1],
            [5280, 2],
            [5299, 3],
            [3273, 1],
            [4988, 2],
            [5411, 3],
            [4394, 2],
        ];
        $this->assertSame($want, array_map('id_lv_pair', $have));
    }
}

function id_lv_pair(array $pair): array
{
    [$item, $level] = $pair;
    return [$item->getId(), $level];
}

class TestItem
{
    protected $item = [];

    public function __construct(array $item)
    {
        $this->item = $item;
    }

    public function getId(): int
    {
        return $this->item["id"];
    }

    public function getKids(): array
    {
        if (key_exists("kids", $this->item)) {
            return $this->item["kids"];
        }
        return [];
    }

    public function isDead(): bool
    {
        return false;
    }

    public function isDeleted(): bool
    {
        return false;
    }
}

class TestClient
{
    private $items;

    public function __construct()
    {
        $this->items = include 'data/items.php';
    }

    public function getItem(int $id): TestItem
    {
        foreach ($this->items as $item) {
            if ($item["id"] == $id) {
                return new TestItem($item);
            }
        }
        throw new Exception("No item with id: $id");
    }
}
