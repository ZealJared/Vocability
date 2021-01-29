<?php
class User extends Model
{
  protected function getTableName(): string
  {
    return 'user';
  }

  protected function getDefaults(): array
  {
    return [
      'token' => $this->generateToken(),
      'active' => 1,
      'admin' => 0
    ];
  }

  public function setName(string $name)
  {
    $this->set('name', $name);
  }

  public function getName(): string
  {
    return strval($this->get('name'));
  }

  public function setPassword(string $password)
  {
    if(empty($password))
    {
      return;
    }
    $this->setRawPassword(password_hash($password, PASSWORD_DEFAULT));
  }

  protected function setRawPassword(string $password)
  {
    if(empty($password))
    {
      return;
    }
    $this->set('password', $password);
  }

  public function getPassword(): string
  {
    return '';
  }

  public function checkPassword(string $password): bool
  {
    $hash = $this->get('password');
    return password_verify($password, $hash);
  }

  protected function generateToken(): string
  {
    return bin2hex(random_bytes(100));
  }

  public function updateToken()
  {
    $this->set('token', $this->generateToken());
  }

  protected function setToken(string $token)
  {
    $this->set('token', $token);
  }

  public function getToken(): string
  {
    return $this->get('token');
  }

  public function setActive($bool)
  {
    $this->set('active', intval((bool) $bool));
  }

  public function getActive(): bool
  {
    return boolval($this->get('active'));
  }

  public function setAdmin($bool)
  {
    $this->set('admin', intval((bool) $bool));
  }

  public function getAdmin(): bool
  {
    return boolval($this->get('admin'));
  }

  /** @return UserWord[] */
  public function getTroubleWords (int $count = 10): array
  {
    return Query::get('UserWord',
    'SELECT
      uw.user_id,
      uw.word_id,
      (
        SELECT
          AVG(score)
        FROM
        (
          SELECT
            uw1.score
          FROM
            user_word uw1
          WHERE
            uw1.word_id = uw.word_id
            AND uw1.user_id = uw.user_id
          ORDER BY
            uw1.created_at DESC
          LIMIT 3
        )
      ) AS score
    FROM
      user_word uw
    WHERE
      uw.user_id = :userId
    GROUP BY
      uw.word_id
    HAVING
      score < 100
    ORDER BY
      score ASC
    LIMIT :count',
    [
      'userId' => $this->getId(),
      'count' => $count
    ]);
  }
}
