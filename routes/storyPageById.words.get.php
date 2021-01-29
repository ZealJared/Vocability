<?php
App::get()->addRoute(new Route(
  'GET',
  '/story_page/{id}/words',
  function(array $args)
  {
    $storyPageId = intval($args['id']);
    if( !App::getUser()->getActive() )
    {
      throw new Exception('Access denied.');
    }
    $storyPages = Query::get('StoryPage', 'SELECT * FROM `story_page` WHERE `id` = :id', [ 'id' => $storyPageId ]);
    if(empty($storyPages))
    {
      throw new Exception("StoryPage with ID $storyPageId not found.");
    }
    /** @var StoryPage $storyPage */
    $storyPage = $storyPages[0];
    $response = new Response();
    $response->setData((object)[
      "words" => $storyPage->getWords()
    ]);
    return $response;
  }
));
