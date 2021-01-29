<?php
class WordList extends Model
{
  /** @var Word[] */
  private $words = null;
  /** @var Word[] */
  private $wordsWithStoryPageId = null;

  protected function getTableName(): string
  {
    return 'word_list';
  }

  protected function getDefaults(): array
  {
    return [
      'name' => ''
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

  public function setName(string $name)
  {
    $this->set('name', $name);
  }

  public function getName(): string
  {
    return $this->get('name');
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
        `word_list_word`
        JOIN `word` ON(`word_list_word`.`word_id` = `word`.`id`)
      WHERE
        `word_list_word`.`word_list_id` = :id',
      [ 'id' => $this->getId() ]);
    }
    return $this->words;
  }

  /** @return Word[] */
  public function getWordsWithStoryPageId(): array
  {
    if(is_null($this->wordsWithStoryPageId))
    {
      $this->wordsWithStoryPageId = Query::get('Word',
      'SELECT
        `word`.*,
        GROUP_CONCAT(`story_page_word`.`story_page_id`) AS `$story_page_id`
      FROM
        `word_list`
        JOIN `word_list_word` ON(`word_list_word`.`word_list_id` = `word_list`.`id`)
        JOIN `word` ON(`word_list_word`.`word_id` = `word`.`id`)
        LEFT JOIN `story_page` ON(`story_page`.`story_id` = `word_list`.`story_id`)
        LEFT JOIN `story_page_word` ON(`story_page_word`.`story_page_id` = `story_page`.`id` AND `story_page_word`.`word_id` = `word`.`id`)
      WHERE
        `word_list`.`id` = :id
      GROUP BY
        `word`.`id`',
      [
        'id' => $this->getId()
      ]);
    }
    return $this->wordsWithStoryPageId;
  }
}
