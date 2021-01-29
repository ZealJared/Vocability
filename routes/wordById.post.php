<?php
App::get()->addRoute(new Route(
  'POST',
  '/word/{id}',
  function(array $args)
  {
    $wordId = intval($args['id']);
    if(!App::getUser()->getAdmin() || !App::getUser()->getActive())
    {
      throw new Exception('Access denied.');
    }
    $words = Query::get('Word', 'SELECT * FROM `word` WHERE `id` = :id', [ 'id' => $wordId ]);
    if(empty($words))
    {
      throw new Exception("Word with ID $wordId not found.");
    }
    /** @var Word $word */
    $word = $words[0];
    $word->setBulkFromObject(Request::getData(), true);
    $word->save(true);
    $response = new Response();
    $response->setData($word);
    return $response;
  }
));
