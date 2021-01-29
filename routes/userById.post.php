<?php
App::get()->addRoute(new Route(
  'POST',
  '/user/{id}',
  function(array $args)
  {
    $userId = intval($args['id']);
    if(!App::getUser()->getAdmin() || !App::getUser()->getActive())
    {
      throw new Exception('Access denied.');
    }
    $users = Query::get('User', 'SELECT * FROM `user` WHERE `id` = :id', [ 'id' => $userId ]);
    if(empty($users))
    {
      throw new Exception("User with ID $userId not found.");
    }
    /** @var User $user */
    $user = $users[0];
    $user->setBulkFromObject(Request::getData(), true);
    $user->save(true);
    $response = new Response();
    $response->setData($user);
    return $response;
  }
));
