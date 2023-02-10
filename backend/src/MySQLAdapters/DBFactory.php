<?php declare(strict_types=1);

namespace MySQLAdapters;

use DB;

class DBFactory
{
  public function __construct(
    string $user,
    string $password,
    string $dbName,
    string $host,
    int $port
  )
  {
    DB::$connect_options = array(MYSQLI_OPT_CONNECT_TIMEOUT => 10);
    DB::$user = $user;
    DB::$password = $password;
    DB::$dbName = $dbName;
    DB::$host = $host;
    DB::$port = $port;
    DB::$encoding = 'utf8';
  }
}

