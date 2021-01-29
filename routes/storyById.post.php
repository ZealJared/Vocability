<?php
App::get()->addRoute(new Route(
  'POST',
  '/story/{id}',
  function(array $args)
  {
    $storyId = intval($args['id']);
    if(!App::getUser()->getAdmin() || !App::getUser()->getActive())
    {
      throw new Exception('Access denied.');
    }
    $stories = Query::get('Story', 'SELECT * FROM `story` WHERE `id` = :id', [ 'id' => $storyId ]);
    if(empty($stories))
    {
      throw new Exception("Story with ID $storyId not found.");
    }
    /** @var Story $story */
    $story = $stories[0];
    $story->setBulkFromObject(Request::getData(), true);
    $story->save(true);
    $response = new Response();
    $response->setData($story);
    return $response;
  }
));
