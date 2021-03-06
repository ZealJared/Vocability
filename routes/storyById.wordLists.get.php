<?php
App::get()->addRoute(new Route(
  'GET',
  '/story/{id}/word_lists',
  function(array $args)
  {
    $storyId = intval($args['id']);
    if( !App::getUser()->getActive() )
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
    $response = new Response();
    $response->setData((object)[
      "wordLists" => $story->getWordLists()
    ]);
    return $response;
  }
));
