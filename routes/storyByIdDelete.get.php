<?php
App::get()->addRoute(new Route(
  'GET',
  '/story/{id}/delete',
  function(array $args)
  {
    $storyId = intval($args['id']);
    if(
      !App::getUser()->getAdmin()
      || !App::getUser()->getActive()
    )
    {
      throw new Exception('Access denied.');
    }
    $stories = Query::get('Story', 'SELECT * FROM `story` WHERE `id` = :id', [ 'id' => $storyId ]);
    if(empty($stories))
    {
      throw new Exception("Story with ID $storyId not found.");
    }
    $story = $stories[0];
    $story->delete();
    $response = new Response();
    $response->setData($story);
    return $response;
  }
));
