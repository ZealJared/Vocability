<?php
class StoryPage extends Model
{
  /** @var Word[] */
  private $words = null;

  protected function getTableName(): string
  {
    return 'story_page';
  }

  protected function getDefaults(): array
  {
    return [
      'story_id' => '',
      'text' => '',
      'illustration' => '',
      'audio' => ''
    ];
  }

  public function setStoryId(int $storyId)
  {
    $this->set('story_id', $storyId);
  }

  public function getStoryId(): int
  {
    return $this->get('story_id');
  }

  public function setText(string $text)
  {
    $this->set('text', $text);
  }

  public function getText(): string
  {
    return $this->get('text');
  }

  public function setIllustration(string $illustration)
  {
    $illustration = Utility::isDataUri($illustration) ? Utility::dataUriToFilePath($this->getTableName(), $this->getId(), 'illustration', $illustration) : $illustration;
    $this->set('illustration', $illustration);
  }

  public function getIllustration(): string
  {
    return Utility::asset($this->get('illustration'));
  }

  /** @return Word[] */
  public function getWords(): array
  {
    if(is_null($this->words))
    {
      $this->words = Query::get('Word',
      'SELECT
        `word`.*
      FROM
        `story_page_word`
        JOIN `word` ON(`story_page_word`.`word_id` = `word`.`id`)
      WHERE
        `story_page_word`.`story_page_id` = :id',
      [ 'id' => $this->getId() ]);
    }
    return $this->words;
  }

  public function setAudio(string $audio)
  {
    $audio = Utility::isDataUri($audio) ? Utility::dataUriToFilePath($this->getTableName(), $this->getId(), 'audio', $audio) : $audio;
    $this->set('audio', $audio);
  }

  public function getAudio(): string
  {
    return Utility::asset($this->get('audio'));
  }

  protected function beforeDelete()
  {
    Utility::deleteRecursive(sprintf('/%s/%d', $this->getTableName(), $this->getId()));
  }
}
