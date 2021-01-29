<?php
App::get()->addRoute(new Route(
  'GET',
  '/story_page/{id}/delete',
  function(array $args)
  {
    $storyPageId = intval($args['id']);
    if(
      !App::getUser()->getAdmin()
      || !App::getUser()->getActive()
    )
    {
      throw new Exception('Access denied.');
    }
    $storyPages = Query::get('StoryPage', 'SELECT * FROM `story_page` WHERE `id` = :id', [ 'id' => $storyPageId ]);
    if(empty($storyPages))
    {
      throw new Exception("StoryPage with ID $storyPageId not found.");
    }
    $storyPage = $storyPages[0];
    $storyPage->delete();
    $response = new Response();
    $response->setData($storyPage);
    return $response;
  }
));
