<?php
App::get()->addRoute(new Route(
  'GET',
  '/stories',
  function(array $args)
  {
    if(!App::getUser()->getActive())
    {
      throw new Exception('Access denied.');
    }
    $response = new Response();
    $response->setData((object)[
      "stories" => Query::get('Story', 'SELECT * FROM `story`')
    ]);
    return $response;
  }
));
