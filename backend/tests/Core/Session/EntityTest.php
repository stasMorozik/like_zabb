<?php declare(strict_types=1);

namespace Tests\Core\Session;

use PHPUnit\Framework\TestCase;
use Core;

class EntityTest extends TestCase
{
  protected static $access_token_salt = 'some_secret';
  protected static $refresh_token_salt = 'some_secret?';

  public function testEncodeSession(): void
  {
    $session = new Core\Session\Entity(
      self::$access_token_salt,
      self::$refresh_token_salt,
      'id'
    );

    $maybe_id = Core\Session\Entity::decode(self::$access_token_salt, $session->access_token);

    $this->assertSame(
      'id',
      $maybe_id
    );
  }

  public function testRefreshSession(): void
  {
    $session = new Core\Session\Entity(
      self::$access_token_salt,
      self::$refresh_token_salt,
      'id'
    );

    $maybe_session = Core\Session\Entity::refresh(self::$access_token_salt, self::$refresh_token_salt, $session->refresh_token);

    $this->assertInstanceOf(
      Core\Session\Entity::class,
      $maybe_session
    );
  }
}
