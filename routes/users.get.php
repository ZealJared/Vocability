<?php
App::get()->addRoute(new Route(
  'GET',
  '/users',
  function(array $args)
  {
    if(!App::getUser()->getActive() || !App::getUser()->getAdmin())
    {
      throw new Exception('Access denied.');
    }
    $response = new Response();
    $response->setData((object)[
      "users" => Query::get('User', 'SELECT * FROM `user`')
    ]);
    return $response;
  }
));
