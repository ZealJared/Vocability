<?php
class UserWord extends Model
{
  protected function getTableName(): string
  {
    return 'user_word';
  }

  protected function getDefaults(): array
  {
    return [];
  }

  public function setUserId(int $userId)
  {
    $this->set('user_id', $userId);
  }

  public function getUserId(): int
  {
    return $this->get('user_id');
  }

  public function setWordId(int $wordId)
  {
    $this->set('word_id', $wordId);
  }

  public function getWordId(): int
  {
    return $this->get('word_id');
  }

  public function setScore (float $score)
  {
    $this->set('score', $score);
  }

  public function getScore (): float
  {
    return $this->get('score');
  }
}
