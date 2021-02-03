<?php
class Config
{
  public static function getBaseUrl(): string
  {
    return "http://localhost/";
  }

  public static function getBaseFilePath(): string
  {
    return dirname(__DIR__);
  }

  public static function getPublicFilePath(): string
  {
    return self::getBaseFilePath() . "/public";
  }

  public static function getStorageFilePath(): string
  {
    return self::getPublicFilePath() . '/storage';
  }

  public static function getStorageBaseUrl(): string
  {
    return self::getBaseUrl() . '/storage';
  }
}
