<?php
class Word extends Model
{
  protected function getTableName(): string
  {
    return 'word';
  }

  protected function getDefaults(): array
  {
    return [
      'spelling' => '',
      'illustration' => '',
      'pronunciation' => ''
    ];
  }

  public function setSpelling(string $spelling)
  {
    $this->set('spelling', $spelling);
  }

  public function setIllustration(string $illustration)
  {
    $illustration = Utility::isDataUri($illustration) ? Utility::dataUriToFilePath($this->getTableName(), $this->getId(), 'illustration', $illustration) : $illustration;
    $this->set('illustration', $illustration);
  }

  public function setPronunciation(string $pronunciation)
  {
    $pronunciation = Utility::isDataUri($pronunciation) ? Utility::dataUriToFilePath($this->getTableName(), $this->getId(), 'pronunciation', $pronunciation) : $pronunciation;
    $this->set('pronunciation', $pronunciation);
  }

  public function getSpelling(): string
  {
    return $this->get('spelling');
  }

  public function getIllustration(): string
  {
    return Utility::asset($this->get('illustration'));
  }

  public function getPronunciation(): string
  {
    return Utility::asset($this->get('pronunciation'));
  }

  protected function beforeDelete()
  {
    Utility::deleteRecursive(sprintf('/%s/%d', $this->getTableName(), $this->getId()));
  }
}
