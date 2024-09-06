<?php

// HackerNews class can convert a multidimensional tree of story
// comments into an array. Each node can have any number of children.
class HackerNews
{
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    public function comments($story) {
        $comments = $this->traverse($story);
        return array_slice($comments, 1); // omit the story
    }

    private function traverse($node) {
        $stack = [[$node, 0]];
        $list = [];
        while (!empty($stack)) {
            list($top, $level) = array_pop($stack);
            $kids = $top->getKids();
            foreach (array_reverse($kids) as $id) {
                $item = $this->client->getItem($id);
                $dead = ($item->isDead() or $item->isDeleted());
                $dead or array_push($stack, [$item, $level + 1]);
            }
            $list[] = [$top, $level];
        }
        return $list;
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
