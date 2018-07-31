<?php

use HackerNewsApi\Service\HackerNewsServiceClient;
use HackerNewsApi\Client\HackerNewsClient;

$items = $app['controllers_factory'];

$items->get('/{type}', function ($type) use ($app) {

    $client = new HackerNewsClient(HackerNewsServiceClient::create());
    $page_limit = 30;
    
    function stories($client, $type) {
        switch ($type) {
            case 'new':
                return $client->getNewStories();
            case 'show':
                return $client->getShowStories();
            case 'ask':
                return $client->getAskStories();
            case 'jobs':
                return $client->getJobStories();
            case 'best':
                return $client->getBestStories();
            default:
                return $client->getTopStories();
        }
    }

    $stories = stories($client, $type);
    $stories = array_slice($stories, 0, $page_limit);
    $items = [];
    
    foreach ($stories as $id) {
        $items[] = $client->getItem($id);
    }
    
    return $app['twig']->render('items.html.twig', ['items' => $items]);
    
})->bind('items')->value('type', 'top');

return $items;