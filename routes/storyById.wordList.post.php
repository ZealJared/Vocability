<?php
App::get()->addRoute(new Route(
  'POST',
  '/story/{id}/word_list',
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
    $wordList = new WordList();
    $wordList->setStoryId($story->getId());
    $wordList->setName(Request::getString('name'));
    $wordList->save();
    $response = new Response();
    $response->setData((object)[
      "wordList" => $wordList
    ]);
    return $response;
  }
));
