<?php

use HackerNewsApi\Service\HackerNewsServiceClient;
use HackerNewsApi\Client\HackerNewsClient;

$comments = $app['controllers_factory'];

$comments->get('/', function ($item_id) use ($app) {

    $client = new HackerNewsClient(HackerNewsServiceClient::create());
    $story = $client->getItem($item_id);

    $comments = (new HackerNewsWrapper($client))->comments($story);

    return $app['twig']->render('comments.html.twig', ['story' => $story, 'comments' => $comments]);

})->bind('comments');

return $comments;