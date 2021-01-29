<?php
class Story extends Model
{
  /** @var WordList[] */
  private $wordLists = null;
  /** @var StoryPage[] */
  private $storyPages = null;

  protected function getTableName(): string
  {
    return 'story';
  }

  protected function getDefaults(): array
  {
    return [
      'title' => ''
    ];
  }

  public function setTitle(string $title)
  {
    $this->set('title', $title);
  }

  public function getTitle(): string
  {
    return $this->get('title');
  }

  /** @return WordList[] */
  public function getWordLists(): array
  {
    if(is_null($this->wordLists))
    {
      $this->wordLists = Query::get('WordList',
      'SELECT
        *
      FROM
        `word_list`
      WHERE
        `word_list`.`story_id` = :id',
      [ 'id' => $this->getId() ]);
    }
    return $this->wordLists;
  }

  /** @return StoryPage[] */
  public function getStoryPages(): array
  {
    if(is_null($this->storyPages))
    {
      $this->storyPages = Query::get('StoryPage',
      'SELECT
        *
      FROM
        `story_page`
      WHERE
        `story_page`.`story_id` = :id',
      [ 'id' => $this->getId() ]);
    }
    return $this->storyPages;
  }
}
