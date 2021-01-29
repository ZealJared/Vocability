<?php
App::get()->addRoute(new Route(
  'POST',
  '/login',
  function(array $args)
  {
    $name = Request::getString('name');
    $password = Request::getString('password');
    /** @var User[] */
    $users = Query::get('User', 'SELECT * FROM `user` WHERE `name` = :name', ['name' => $name]);
    if(empty($users))
    {
      throw new Exception('No such user.');
    }
    $user = $users[0];
    if(!$user->checkPassword($password))
    {
      throw new Exception('Password incorrect.');
    }
    $user->updateToken();
    $user->save();
    $response = new Response();
    $response->setData((object)[
      "user" => $user
    ]);
    return $response;
  }
));
