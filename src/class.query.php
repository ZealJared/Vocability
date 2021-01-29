<?php
class Query
{
  public static function get(string $className, string $sql, array $input = []): array
  {
    /** @var array */
    $objects = [];
    $statement = Database::getConnection()->prepare($sql);
    $statement->execute($input);
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach($records as $record)
    {
      $object = new $className();
      $object->setBulk($record);
      $objects[] = $object;
    }
    return $objects;
  }
}
