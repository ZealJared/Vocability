<?php
App::get()->addRoute(new Route(
  'POST',
  '/story',
  function(array $args)
  {
    if(!App::getUser()->getActive() || !App::getUser()->getAdmin())
    {
      throw new Exception('Access denied.');
    }
    $story = new Story();
    $story->setTitle(Request::getString('title'));
    $story->save();
    $response = new Response();
    $response->setData((object)[
      "story" => $story
    ]);
    return $response;
  }
));
