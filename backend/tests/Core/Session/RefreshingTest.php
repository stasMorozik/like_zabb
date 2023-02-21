<?php declare(strict_types=1);

namespace Tests\Core\Session;

use PHPUnit\Framework\TestCase;
use Core;

class RefreshingTest extends TestCase
{
  protected static $access_token_salt = 'some_secret';
  protected static $refresh_token_salt = 'some_secret?';
  protected static $refreshing_use_case;

  protected function setUp(): void
  {
    self::$refreshing_use_case = new Core\Session\UseCases\Refreshing(
      self::$access_token_salt,
      self::$refresh_token_salt
    );
  }

  public function testRefresh(): void
  {
    $session = Core\Session\Entity::new([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => 'id'
    ]);

    $maybe_session = self::$refreshing_use_case->refresh(['refresh_token' => $session->getRefreshToken()]);

    $this->assertInstanceOf(
      Core\Session\Entity::class,
      $maybe_session
    );
  }

  public function testInvalidToken(): void
  {
    $maybe_session = self::$refreshing_use_case->refresh(['refresh_token' => 'invalid token']);

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_session
    );
  }
}
