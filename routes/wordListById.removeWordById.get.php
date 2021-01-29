<?php
App::get()->addRoute(new Route(
  'GET',
  '/word_list/{wordListId}/remove_word/{wordId}',
  function(array $args)
  {
    $wordListId = intval($args['wordListId']);
    $wordId = intval($args['wordId']);
    if( !App::getUser()->getActive() || !App::getUser()->getAdmin() )
    {
      throw new Exception('Access denied.');
    }
    $wordLists = Query::get('WordList', 'SELECT * FROM `word_list` WHERE `id` = :id', [ 'id' => $wordListId ]);
    if(empty($wordLists))
    {
      throw new Exception("WordList with ID $wordListId not found.");
    }
    $words = Query::get('Word', 'SELECT * FROM `word` WHERE `id` = :id', [ 'id' => $wordId ]);
    if(empty($words))
    {
      throw new Exception("Word with ID $wordId not found.");
    }
    /** @var WordList $wordList */
    $wordList = $wordLists[0];
    /** @var Word $word */
    $word = $words[0];

    $wordListWords = Query::get('WordListWord', 'SELECT * FROM `word_list_word` WHERE `word_list_id` = :wordListId AND `word_id` = :wordId', [
      'wordListId' => $wordList->getId(),
      'wordId' => $word->getId()
    ]);
    if(empty($wordListWords))
    {
      throw new Exception("WordListWord linking WordList " . $wordList->getId() . " and Word " . $word->getId() . " not found.");
    }
    $wordListWord = $wordListWords[0];
    $wordListWord->delete();

    $response = new Response();
    $response->setData($wordListWord);
    return $response;
  }
));
