<?php
App::get()->addRoute(new Route(
  'POST',
  '/word',
  function(array $args)
  {
    if(!App::getUser()->getActive() || !App::getUser()->getAdmin())
    {
      throw new Exception('Access denied.');
    }
    $word = new Word();
    $word->setSpelling(Request::getString('spelling'));
    $word->setIllustration(Request::getString('illustration'));
    $word->setPronunciation(Request::getString('pronunciation'));
    $word->save();
    $response = new Response();
    $response->setData((object)[
      "word" => $word
    ]);
    return $response;
  }
));
