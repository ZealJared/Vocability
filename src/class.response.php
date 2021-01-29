<?php
class Response
{
  /** @var object */
  private $data;

  public function __construct()
  {
    $this->data = (object)[];
  }

  public function setData(object $data)
  {
    $this->data = $data;
  }

  public function render()
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Authorization');
    die(json_encode($this->data, JSON_PRETTY_PRINT));
  }
}
