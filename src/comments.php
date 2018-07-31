<?php

use HackerNewsApi\Service\HackerNewsServiceClient;
use HackerNewsApi\Client\HackerNewsClient;

$comments = $app['controllers_factory'];

$comments->get('/', function ($item_id) use ($app) {
    
    $client = new HackerNewsClient(HackerNewsServiceClient::create());
    $story = $client->getItem($item_id);
    
    function kids($parent) {
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
                    $children = array_merge($children, [$item], kids($item));
                }
            }
            return $children;
        }
    };

    $items = kids($story);

    function time_asc($a, $b) {
        return ($a->getTime() <=> $b->getTime());
    }

    usort($items, 'time_asc');

    // Create a sorted array of items with indentation levels
    $comments = flatten(tree($items, $item_id));

    return $app['twig']->render('comments.html.twig', ['story' => $story, 'comments' => $comments]);
    
})->bind('comments');

return $comments;