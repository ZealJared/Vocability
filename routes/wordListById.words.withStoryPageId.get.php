<?php
App::get()->addRoute(new Route(
  'GET',
  '/word_list/{id}/words/include_story_page_id',
  function(array $args)
  {
    $wordListId = intval($args['id']);
    if( !App::getUser()->getActive() )
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
    $response = new Response();
    $response->setData((object)[
      "words" => $wordList->getWordsWithStoryPageId()
    ]);
    return $response;
  }
));
