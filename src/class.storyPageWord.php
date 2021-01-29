<?php
class StoryPageWord extends Model
{
  protected function getTableName(): string
  {
    return 'story_page_word';
  }

  protected function getDefaults(): array
  {
    return [];
  }

  public function setStoryPageId(int $storyPageId)
  {
    $this->set('story_page_id', $storyPageId);
  }

  public function getStoryPageId(): int
  {
    return $this->get('story_page_id');
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
