<?php
class Request
{
  /** @var object */
  private static $data;

  public static function getMethod(): string
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public static function getPath(): string
  {
    $path = $_SERVER['REQUEST_URI'];
    return preg_replace('~(.)/$~', '$1', $path);
  }

  public static function getData(): object
  {
    if(!empty(self::$data))
    {
      return self::$data;
    }
    if(!empty($_POST))
    {
      self::$data = (object)$_POST;
    } else {
      self::$data = (object)json_decode(file_get_contents('php://input'));
    }
    return self::$data;
  }

  public static function getString(string $name): string
  {
    $data = self::getData();
    if(!isset($data->$name))
    {
      throw new Exception("No data for '$name' in request body.");
    }
    if(!is_string($data->$name))
    {
      throw new Exception("Attempted to get non-string value for '$name' as string from request body.");
    }
    return $data->$name;
  }

  public static function getInt(string $name): int
  {
    $data = self::getData();
    if(!isset($data->$name))
    {
      throw new Exception("No data for '$name' in request body.");
    }
    if(!is_int($data->$name))
    {
      throw new Exception("Attempted to get non-integer value for '$name' as integer from request body.");
    }
    return intval($data->$name, 10);
  }

  public static function getFloat(string $name): float
  {
    $data = self::getData();
    if(!isset($data->$name))
    {
      throw new Exception("No data for '$name' in request body.");
    }
    if(!is_numeric($data->$name))
    {
      throw new Exception("Attempted to get non-float value for '$name' as float from request body.");
    }
    return floatval($data->$name);
  }

  public static function getHeader(string $name): string
  {
    $headerName = strtoupper($name);
    return $_SERVER['HTTP_' . $headerName] ?? null;
  }

  public static function getAuth(): array
  {
    [ $method, $base64AuthString ] = explode(' ', self::getHeader('Authorization'), 2);
    $authString = base64_decode($base64AuthString);
    [ $name, $token ] = explode(':', $authString, 2);
    return [
      'name' => $name,
      'token' => $token
    ];
  }
}
