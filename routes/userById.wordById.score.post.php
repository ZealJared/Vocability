<?php
App::get()->addRoute(new Route(
  'POST',
  '/user/{userId}/word/{wordId}/score',
  function(array $args)
  {
    $userId = intval($args['userId']);
    $wordId = intval($args['wordId']);
    if(!(App::getUser()->getId() == $userId || App::getUser()->getAdmin()) || !App::getUser()->getActive())
    {
      throw new Exception('Access denied.');
    }
    $users = Query::get('User', 'SELECT * FROM `user` WHERE `id` = :id', [ 'id' => $userId ]);
    if(empty($users))
    {
      throw new Exception("User with ID $userId not found.");
    }
    $words = Query::get('Word', 'SELECT * FROM `word` WHERE `id` = :id', [ 'id' => $wordId ]);
    if(empty($words))
    {
      throw new Exception("Word with ID $wordId not found.");
    }
    /** @var User $user */
    $user = $users[0];
    /** @var Word $word */
    $word = $words[0];
    $userWord = new UserWord();
    $userWord->setUserId($user->getId());
    $userWord->setWordId($word->getId());
    $userWord->setScore(Request::getFloat('score'));
    $userWord->save();
    $response = new Response();
    $response->setData($userWord);
    return $response;
  }
));
