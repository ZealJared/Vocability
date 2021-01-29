<?php
class WordListWord extends Model
{
  protected function getTableName(): string
  {
    return 'word_list_word';
  }

  protected function getDefaults(): array
  {
    return [];
  }

  public function setWordListId(int $wordListId)
  {
    $this->set('word_list_id', $wordListId);
  }

  public function getWordListId(): int
  {
    return $this->get('word_list_id');
  }

  public function setWordId(int $wordId)
  {
    $this->set('word_id', $wordId);
  }

  public function getWordId(): int
  {
    return $this->get('word_id');
  }
}
