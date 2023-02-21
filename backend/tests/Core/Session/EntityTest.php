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
    $session = Core\Session\Entity::new([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => 'id'
    ]);

    $maybe_id = Core\Session\Entity::decode([
      'access_token_salt' => self::$access_token_salt,
      'access_token' => $session->getAccessToken()
    ]);

    $this->assertSame(
      'id',
      $maybe_id
    );
  }

  public function testRefreshSession(): void
  {
    $session = Core\Session\Entity::new([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => 'id'
    ]);

    $maybe_session = Core\Session\Entity::refresh([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'refresh_token' => $session->getRefreshToken()
    ]);

    $this->assertInstanceOf(
      Core\Session\Entity::class,
      $maybe_session
    );
  }
}
