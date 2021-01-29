<?php
App::get()->addRoute(new Route(
  'GET',
  '/user/{id}',
  function(array $args)
  {
    $userId = intval($args['id']);
    if(
      !(App::getUser()->getId() == $userId || App::getUser()->getAdmin())
      || !App::getUser()->getActive()
    )
    {
      throw new Exception('Access denied.');
    }
    $users = Query::get('User', 'SELECT * FROM `user` WHERE `id` = :id', [ 'id' => $userId ]);
    if(empty($users))
    {
      throw new Exception("User with ID $userId not found.");
    }
    $user = $users[0];
    $response = new Response();
    $response->setData($user);
    return $response;
  }
));
