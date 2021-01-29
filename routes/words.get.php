<?php
App::get()->addRoute(new Route(
  'GET',
  '/words',
  function(array $args)
  {
    if( !App::getUser()->getActive() )
    {
      throw new Exception('Access denied.');
    }
    $response = new Response();
    $response->setData((object)[
      "words" => Query::get('Word', 'SELECT * FROM `word` ORDER BY `spelling` ASC')
    ]);
    return $response;
  }
));
