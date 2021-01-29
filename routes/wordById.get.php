<?php
App::get()->addRoute(new Route(
  'GET',
  '/word/{id}',
  function(array $args)
  {
    $wordId = intval($args['id']);
    if( !App::getUser()->getActive() )
    {
      throw new Exception('Access denied.');
    }
    $words = Query::get('Word', 'SELECT * FROM `word` WHERE `id` = :id', [ 'id' => $wordId ]);
    if(empty($words))
    {
      throw new Exception("Word with ID $wordId not found.");
    }
    $word = $words[0];
    $response = new Response();
    $response->setData($word);
    return $response;
  }
));
