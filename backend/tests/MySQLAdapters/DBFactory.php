<?php declare(strict_types=1);

namespace Tests\MySQLAdapters;

use MySQLAdapters;
use Dotenv\Dotenv;

class DBFactory
{
  public static function factory()
  {
    Dotenv::createUnsafeImmutable(__DIR__ . '/../../', '.env.test')->load();
    return new MySQLAdapters\DBFactory(
      $_ENV["DB_USER"],
      $_ENV["DB_PASSWORD"],
      $_ENV["DB_NAME"],
      $_ENV["DB_HOST"],
      (int) $_ENV["DB_PORT"]
    );
  }
}
