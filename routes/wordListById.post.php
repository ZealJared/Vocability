<?php
App::get()->addRoute(new Route(
  'POST',
  '/word_list/{id}',
  function(array $args)
  {
    $wordListId = intval($args['id']);
    if(!App::getUser()->getAdmin() || !App::getUser()->getActive())
    {
      throw new Exception('Access denied.');
    }
    $wordLists = Query::get('WordList', 'SELECT * FROM `word_list` WHERE `id` = :id', [ 'id' => $wordListId ]);
    if(empty($wordLists))
    {
      throw new Exception("WordList with ID $wordListId not found.");
    }
    /** @var WordList $wordList */
    $wordList = $wordLists[0];
    $wordList->setBulkFromObject(Request::getData(), true);
    $wordList->save(true);
    $response = new Response();
    $response->setData($wordList);
    return $response;
  }
));
