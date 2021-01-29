<?php
class Route
{
  /** @var array */
  protected $args = [];
  protected $method;
  protected $pattern;
  protected $response;

  public function __construct(string $method, string $pattern, callable $response)
  {
    $this->method = strtoupper($method);
    $this->pattern = $pattern;
    $this->response = $response;
  }

  public function match(): bool
  {
    if($this->getMethod() === Request::getMethod())
    {
      $realPattern = "~^" . preg_replace('~\{([^\}]+)\}~', '(?P<$1>[^/]+)', $this->getPattern()) . "$~";
      preg_match($realPattern, Request::getPath(), $this->args);
      if(!empty($this->args))
      {
        return true;
      }
    }
    return false;
  }
  protected function getMethod(): string
  {
    return $this->method;
  }
  protected function getPattern(): string
  {
    return $this->pattern;
  }
  public function getResponse(): Response
  {
    $func = $this->response;
    return $func($this->args);
  }
}
