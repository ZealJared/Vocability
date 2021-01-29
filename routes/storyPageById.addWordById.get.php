<?php
App::get()->addRoute(new Route(
  'GET',
  '/story_page/{storyPageId}/add_word/{wordId}',
  function(array $args)
  {
    $storyPageId = intval($args['storyPageId']);
    $wordId = intval($args['wordId']);
    if( !App::getUser()->getActive() || !App::getUser()->getAdmin() )
    {
      throw new Exception('Access denied.');
    }
    $storyPages = Query::get('StoryPage', 'SELECT * FROM `story_page` WHERE `id` = :id', [ 'id' => $storyPageId ]);
    if(empty($storyPages))
    {
      throw new Exception("StoryPage with ID $storyPageId not found.");
    }
    $words = Query::get('Word', 'SELECT * FROM `word` WHERE `id` = :id', [ 'id' => $wordId ]);
    if(empty($words))
    {
      throw new Exception("Word with ID $wordId not found.");
    }
    /** @var StoryPage $wordList */
    $storyPage = $storyPages[0];
    /** @var Word $word */
    $word = $words[0];

    $storyPageWord = new StoryPageWord();
    $storyPageWord->setStoryPageId($storyPage->getId());
    $storyPageWord->setWordId($word->getId());
    $storyPageWord->save();

    $response = new Response();
    $response->setData((object)[
      "storyPageWord" => $storyPageWord
    ]);
    return $response;
  }
));
