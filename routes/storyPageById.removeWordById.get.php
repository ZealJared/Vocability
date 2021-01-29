<?php
App::get()->addRoute(new Route(
  'GET',
  '/story_page/{storyPageId}/remove_word/{wordId}',
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
    /** @var StoryPage $storyPage */
    $storyPage = $storyPages[0];
    /** @var Word $word */
    $word = $words[0];

    $storyPageWords = Query::get('StoryPageWord', 'SELECT * FROM `story_page_word` WHERE `story_page_id` = :storyPageId AND `word_id` = :wordId', [
      'storyPageId' => $storyPage->getId(),
      'wordId' => $word->getId()
    ]);
    if(empty($storyPageWords))
    {
      throw new Exception("StoryPageWord linking WordList " . $storyPage->getId() . " and Word " . $word->getId() . " not found.");
    }
    $storyPageWord = $storyPageWords[0];
    $storyPageWord->delete();

    $response = new Response();
    $response->setData($storyPageWord);
    return $response;
  }
));
