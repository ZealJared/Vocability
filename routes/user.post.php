<?php
App::get()->addRoute(new Route(
  'POST',
  '/user',
  function(array $args)
  {
    if(!App::getUser()->getActive() || !App::getUser()->getAdmin())
    {
      throw new Exception('Access denied.');
    }
    $user = new User();
    $user->setName(Request::getString('name'));
    $user->setPassword(Request::getString('password'));
    $user->save();
    $response = new Response();
    $response->setData((object)[
      "user" => $user
    ]);
    return $response;
  }
));
