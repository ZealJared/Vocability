<?php
App::get()->addRoute(new Route(
  'GET',
  '/word_list/{id}/delete',
  function(array $args)
  {
    $wordListId = intval($args['id']);
    if(
      !App::getUser()->getAdmin()
      || !App::getUser()->getActive()
    )
    {
      throw new Exception('Access denied.');
    }
    $wordLists = Query::get('WordList', 'SELECT * FROM `word_list` WHERE `id` = :id', [ 'id' => $wordListId ]);
    if(empty($wordLists))
    {
      throw new Exception("WordList with ID $wordListId not found.");
    }
    $wordList = $wordLists[0];
    $wordList->delete();
    $response = new Response();
    $response->setData($wordList);
    return $response;
  }
));
