<?php
class Utility
{
  public static function isDataUri (string $data): bool
  {
    return preg_match("~^data:~", $data) === 1;
  }

  public static function dataUriToFilePath (string $object, int $id, string $field, string $data): string
  {
    $parts = [];
    preg_match("~^data:[^/]+/(?P<filetype>[^;]+);base64,(?P<data>.*)~", $data, $parts);
    $webPath = sprintf("/%s/%d/%s.%s", $object, $id, $field, $parts['filetype']);
    $filePath = Config::getStorageFilePath() . $webPath;
    $dir = dirname($filePath);
    if(!is_dir($dir))
    {
      mkdir($dir, 0777, true);
    }
    file_put_contents($filePath, base64_decode($parts['data']));
    return $webPath;
  }

  public static function deleteRecursive (string $filePath)
  {
    if(empty($filePath))
    {
      throw new Exception("No path provided for delete.");
    }
    $storagePath = Config::getStorageFilePath();
    if(!strstr($filePath, $storagePath))
    {
      $filePath = $storagePath . $filePath;
    }
    if(!is_dir($filePath))
    {
      if (!is_file($filePath))
      {
        return;
      }
      unlink($filePath);
    } else {
      $content = glob("$filePath/*");
      if(!empty($content))
      {
        array_map('Utility::deleteRecursive', $content);
      }
      rmdir($filePath);
    }
  }

  public static function asset(string $path): string
  {
    if (empty($path)) {
      return '';
    }
    return Config::getStorageBaseUrl() . $path;
  }

  public static function log(string $message)
  {
    file_put_contents(Config::getBaseFilePath() . '/logs/debug.log', $message . "\n", FILE_APPEND);
  }
}
