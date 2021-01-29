<?php
App::get()->addRoute(new Route(
  'POST',
  '/story/{id}/story_page',
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
    $storyPage = new StoryPage();
    $storyPage->setStoryId($story->getId());
    $storyPage->setText(Request::getString('text'));
    $storyPage->save();
    $response = new Response();
    $response->setData((object)[
      "storyPage" => $storyPage
    ]);
    return $response;
  }
));
