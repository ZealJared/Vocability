<?php
App::get()->addRoute(new Route(
  'GET',
  '/word_list/{wordListId}/add_word/{wordId}',
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

    $wordListWord = new WordListWord();
    $wordListWord->setWordListId($wordList->getId());
    $wordListWord->setWordId($word->getId());
    $wordListWord->save();

    $response = new Response();
    $response->setData((object)[
      "wordListWord" => $wordListWord
    ]);
    return $response;
  }
));
