<?php
abstract class Model implements JsonSerializable
{
  /** @var array */
  protected $data;
  protected $extra = [];
  protected $changed = false;
  abstract protected function getTableName(): string;
  abstract protected function getDefaults(): array;

  public function __construct()
  {
    $this->data = $this->getDefaults();
  }

  private function setId($id)
  {
    $this->data['id'] = intval($id);
  }

  public function getId(): int
  {
    return $this->data['id'] ?? 0;
  }

  private function preSave()
  {
    $this->setUpdatedAt(new DateTime());
    if(method_exists($this, 'beforeSave')) 
    {
      $this->beforeSave();
    }
  }

  public function save()
  {
    if($this->getDeleted())
    {
      throw new Exception('Attempted to save a deleted ' . get_called_class() . ' object.');
    }
    if(empty($this->data))
    {
      throw new Exception('Attempted to save an empty ' . get_called_class() . ' object.');
    }
    if(!$this->changed)
    {
      return;
    }
    $this->changed = false;
    $this->preSave();
    if(!$this->getId())
    {
      return $this->insert();
    }
    return $this->update();
  }

  private function preInsert()
  {
    $this->setCreatedAt(new DateTime());
    if(method_exists($this, 'beforeInsert')) 
    {
      $this->beforeInsert();
    }
  }

  private function insert()
  {
    $this->preInsert();
    $data = $this->data;
    unset($data['id']);
    $fields = implode('`, `', array_keys($data));
    $values = implode(', :', array_keys($data));
    $table = $this->getTableName();
    $db = Database::getConnection();
    $statement = $db->prepare("INSERT INTO `$table` (`$fields`) VALUES (:$values)");
    $statement->execute($data);
    $this->setId($db->lastInsertId());
  }

  private function update()
  {
    $data = $this->data;
    $setStringArray = [];
    foreach(array_keys($data) as $key)
    {
      $setStringArray[] = "`$key` = :$key";
    }
    $setString = implode(', ', $setStringArray);
    $tableName = $this->getTableName();
    $db = Database::getConnection();
    $sql = "UPDATE `$tableName` SET $setString WHERE `id` = :id";
    $statement = $db->prepare($sql);
    $statement->execute($data);
  }

  private function preDelete()
  {
    if(method_exists($this, 'beforeDelete'))
    {
      $this->beforeDelete();
    }
  }

  public function delete()
  {
    $this->preDelete();
    if($this->getId())
    {
      $tableName = $this->getTableName();
      $db = Database::getConnection();
      $sql = "DELETE FROM `$tableName` WHERE `id` = :id";
      $statement = $db->prepare($sql);
      $statement->execute(['id' => $this->getId()]);
    }
    $this->setDeleted(true);
  }

  protected function set(string $field, $value)
  {
    if(isset($this->data[$field]) && $this->data[$field] == $value)
    {
      return;
    }
    $this->data[$field] = $value;
    $this->changed = true;
  }

  protected function setExtra(string $field, $value)
  {
    $this->extra[$field] = $value;
  }

  protected function get($field)
  {
    return $this->data[$field] ?? null;
  }

  public function setBulk(array $data)
  {
    foreach($data as $field => $value)
    {
      $baseName = str_replace('_', '', ucwords($field, '_'));
      $priorityMethodName = "setRaw$baseName";
      $methodName = "set$baseName";
      if(method_exists($this, $priorityMethodName))
      {
        $this->$priorityMethodName($value);
      } else if(method_exists($this, $methodName)) {
        $this->$methodName($value);
      } else if(preg_match('~^\$~', $baseName)) {
        $this->setExtra($baseName, $value);
      } else {
        throw new Exception("Method $methodName not found on class " . get_called_class());
      }
    }
    $this->changed = false;
  }

  public function setBulkFromObject(object $data, bool $mayChange = false)
  {
    foreach($data as $field => $value)
    {
      $methodName = "set$field";
      if(method_exists($this, $methodName))
      {
        $this->$methodName($value);
      } else {
        throw new Exception("Method $methodName not found on class " . get_called_class());
      }
    }
    if(!$mayChange)
    {
      $this->changed = false;
    }
  }

  public function setCreatedAt($value)
  {
    if($value instanceof DateTime)
    {
      $saveValue = $value->format('Y-m-d G:i:s');
    } else {
      $saveValue = (new DateTime($value))->format('Y-m-d G:i:s');
    }
    $this->set('created_at', $saveValue);
  }

  public function setUpdatedAt($value)
  {
    if($value instanceof DateTime)
    {
      $saveValue = $value->format('Y-m-d G:i:s');
    } else {
      $saveValue = (new DateTime($value))->format('Y-m-d G:i:s');
    }
    $this->set('updated_at', $saveValue);
  }

  public function getCreatedAt(): DateTime
  {
    return new DateTime($this->get('created_at'));
  }

  public function getUpdatedAt(): DateTime
  {
    return new DateTime($this->get('updated_at'));
  }

  public function getDeleted (): bool
  {
    return boolval($this->get('deleted'));
  }

  protected function setDeleted (bool $deleted)
  {
    $this->set('deleted', $deleted);
  }

  public function jsonSerialize()
  {
    $array = [];
    foreach($this->data as $field => $value)
    {
      $fieldName = str_replace('_', '', ucwords($field, '_'));
      $methodName = "get$fieldName";
      if(method_exists($this, $methodName))
      {
        $value = $this->$methodName();
        if($value instanceof DateTime)
        {
          $value = $value->format("Y-m-d H:i:s");
        }
        $array[$fieldName] = $value;
      } else {
        throw new Exception("Method $methodName not found on class" . self::class);
      }
    }
    foreach($this->extra as $field => $value)
    {
      $array['extra'][$field] = $value;
    }
    return $array;
  }
}
