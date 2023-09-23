<?php

use HackerNewsApi\Service\HackerNewsServiceClient;
use HackerNewsApi\Client\HackerNewsClient;

$comments = $app['controllers_factory'];

$comments->get('/', function ($item_id) use ($app) {
    
    $client = new HackerNewsClient(HackerNewsServiceClient::create());
    $story = $client->getItem($item_id);
    
    // Returns all children of a parent node, recursively.
    // Items are retrieved from the Hacker News API 
    function kids($parent, $level = 1) {
        $kids = $parent->getKids();
        if (count($kids) == 0) {
            return [];
        } else {
            $client = new HackerNewsClient(HackerNewsServiceClient::create());
            $children = [];
            foreach ($kids as $id) {
                $item = $client->getItem($id);
                $dead = ($item->isDead() || $item->isDeleted());
                if (!$dead) {
                    $pair = [$item, $level];
                    $children = array_merge($children, [$pair], kids($item, $level + 1));
                }
            }
            return $children;
        }
    }

    $comments = kids($story);

    return $app['twig']->render('comments.html.twig', ['story' => $story, 'comments' => $comments]);
    
})->bind('comments');

return $comments;