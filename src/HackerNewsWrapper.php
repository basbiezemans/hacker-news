<?php

// HackerNewsWrapper class provides access to HN stories of any type and
// can convert a multidimensional tree of story comments into an array.
// Each comment node can have any number of children.
class HackerNewsWrapper
{
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    public function comments($story) {
        $stack = [[$story, 0]];
        $comments = [];
        while (!empty($stack)) {
            list($node, $level) = array_pop($stack);
            $kids = $node->getKids();
            foreach (array_reverse($kids) as $id) {
                $item = $this->client->getItem($id);
                $dead = ($item->isDead() or $item->isDeleted());
                $dead or array_push($stack, [$item, $level + 1]);
            }
            $comments[] = [$node, $level];
        }
        return array_slice($comments, 1); // omit the story
    }

    public function stories($type, $limit = 30) {
        $getItem = fn($id) => $this->client->getItem($id);
        return array_map($getItem, $this->storyIds($type, $limit));
    }

    private function storyIds($type, $limit) {
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
