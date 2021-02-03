<?php
class Database
{
  /** @var PDO */
  private static $db;
  public static function getConnection(): PDO
  {
    if(empty(self::$db))
    {
      self::$db = new PDO("sqlite:" . Config::getBaseFilePath() . "/db/db.sqlite3");
      self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return self::$db;
  }
}
