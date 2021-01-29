<?php
class App
{
  /** @var App */
  private static $instance = null;
  /** @var User */
  private static $user = null;

  /** @var Route[] */
  private $routes = [];

  private function __construct()
  {
    // do nothing special
  }

  public static function get()
  {
    if(empty(self::$instance))
    {
      self::$instance = new App();
    }
    return self::$instance;
  }

  public function addRoute(Route $route)
  {
    $this->routes[] = $route;
  }

  public function route(): Response
  {
    $realFile = Config::getPublicFilePath() . Request::getPath();
    if(Request::getMethod() === 'GET' && is_file($realFile))
    {
      header('Content-Type: ' . mime_content_type($realFile));
      die(file_get_contents($realFile));
    }
    foreach($this->routes as $route)
    {
      if($route->match())
      {
        try {
          $response = $route->getResponse();
        } catch(Throwable $e) {
          $response = $this->exceptionResponse($e);
        }
        return $response;
      }
    }
    return $this->notFoundResponse();
  }

  private function exceptionResponse(Throwable $e)
  {
    $errorResponse = new Response();
    $errorResponse->setData((object)[
      "error" => $e->getMessage(),
      "trace" => $e->getTrace()
    ]);
    return $errorResponse;
  }

  private function notFoundResponse(): Response
  {
    $errorResponse = new Response();
    $errorResponse->setData((object)[
      "error" => "No matching route found."
    ]);
    return $errorResponse;
  }

  public static function getUser(): User
  {
    if(!empty(self::$user))
    {
      return self::$user;
    }
    $users = Query::get('User', "SELECT * FROM `user` WHERE `name` = :name AND `token` = :token LIMIT 1", Request::getAuth());
    if(!empty($users))
    {
      self::$user = $users[0];
      return self::$user;
    }
    throw new Exception('Invalid authorization credentials.');
  }
}
