<?php

use HackerNewsApi\Service\HackerNewsServiceClient;
use HackerNewsApi\Client\HackerNewsClient;

$comments = $app['controllers_factory'];

$comments->get('/', function ($item_id) use ($app) {
    
    $client = new HackerNewsClient(HackerNewsServiceClient::create());
    $story = $client->getItem($item_id);
    
    // Returns all children of a parent node, recursively.
    // Items are retrieved from the Hacker News API 
    function kids($parent, $client, $level = 1) {
        $children = [];
        foreach ($parent->getKids() as $id) {
            $item = $client->getItem($id);
            $dead = ($item->isDead() or $item->isDeleted());
            if (!$dead) {
                $pair = [$item, $level];
                $children = [...$children, $pair, ...kids($item, $client, $level + 1)];
            }
        }
        return $children;
    }

    $comments = kids($story, $client);

    return $app['twig']->render('comments.html.twig', ['story' => $story, 'comments' => $comments]);
    
})->bind('comments');

return $comments;