<?php
App::get()->addRoute(new Route(
  'POST',
  '/story_page/{id}',
  function(array $args)
  {
    $storyPageId = intval($args['id']);
    if(!App::getUser()->getAdmin() || !App::getUser()->getActive())
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
    $storyPage->setBulkFromObject(Request::getData(), true);
    $storyPage->save(true);
    $response = new Response();
    $response->setData($storyPage);
    return $response;
  }
));
