<?php declare(strict_types=1);

namespace Tests\Core\Session;

use PHPUnit\Framework\TestCase;
use Core;

class RefreshingTest extends TestCase
{
  protected static $access_token_salt = 'some_secret';
  protected static $refresh_token_salt = 'some_secret?';
  protected $refreshing_use_case;

  protected function setUp(): void
  {
    $this->refreshing_use_case = new Core\Session\UseCases\Refreshing(
      self::$access_token_salt,
      self::$refresh_token_salt
    );
  }

  public function testRefresh(): void
  {
    $session = new Core\Session\Entity(
      self::$access_token_salt,
      self::$refresh_token_salt,
      'id'
    );

    $maybe_session = $this->refreshing_use_case->refresh($session->refresh_token);

    $this->assertInstanceOf(
      Core\Session\Entity::class,
      $maybe_session
    );
  }

  public function testInvalidToken(): void
  {
    $maybe_session = $this->refreshing_use_case->refresh('invalid token');

    $this->assertInstanceOf(
      Core\Common\Errors\Domain::class,
      $maybe_session
    );
  }
}
