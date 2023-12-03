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

    public function stories($type, $limit = 30) {
        $getItem = fn($id) => $this->client->getItem($id);
        return array_map($getItem, $this->getStoryIds($type, $limit));
    }

    private function getStoryIds($type, $limit) {
        $getStories = sprintf('get%sStories', story_type($type));
        return array_slice($this->client->$getStories(), 0, $limit);
    }
}

function is_valid($type) {
    $valid = [
        'new', 'show', 'ask',
        'jobs', 'best', 'top',
    ];
    return in_array($type, $valid);
}

function story($type) {
    return ucfirst(rtrim($type, 's'));
}

function story_type($type) {
    return (is_valid($type) ? story($type) : 'Top');
}
