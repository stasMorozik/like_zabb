<?php declare(strict_types=1);

namespace Tests\MySQLAdapters;

use MySQLAdapters;

class DBFactory
{
  public static function factory()
  {
    return new MySQLAdapters\DBFactory(
      'dbuser',
      '12345',
      'uvrcloud',
      'localhost',
      3306
    );
  }
}