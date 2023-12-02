<?php

// HackerNews class can convert a multidimensional tree of story
// comments into an array. Each node can have any number of children.
class HackerNews
{
    private $client;
    private $list = [];

    public function __construct($client) {
        $this->client = $client;
    }

    public function comments($story) {
        $this->listFromTree($story);
        return array_slice($this->list, 1); // omit the story
    }

    private function listFromTree($node, $level = 0) {
        $this->list[] = [$node, $level];
        foreach ($node->getKids() as $id) {
            $item = $this->client->getItem($id);
            $dead = ($item->isDead() or $item->isDeleted());
            !$dead and $this->listFromTree($item, $level + 1);
        }
    }
}
