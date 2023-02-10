<?php declare(strict_types=1);

use Dotenv\Dotenv;

require '../vendor/autoload.php';

Dotenv::createUnsafeImmutable(__DIR__ . '/../', '.env')->load();

DB::$user = $_ENV["DB_USER"];
DB::$password = $_ENV["DB_PASSWORD"];
DB::$dbName = $_ENV["DB_NAME"];
DB::$host = $_ENV["DB_HOST"];
DB::$port = $_ENV["DB_PORT"];
DB::$encoding = 'utf8';
DB::$connect_options = array(MYSQLI_OPT_CONNECT_TIMEOUT => 10);

DB::query("CREATE TABLE users(
  id BINARY(64) not null,
  name varchar(128) not null,
  created date not null,
  email varchar(128) unique not null,
  password varchar(128) not null,
  primary key(id)
)");

DB::query("CREATE TABLE accounts(
  id BINARY(64) not null,
  created date not null,
  email varchar(128) unique not null,
  primary key(id)
)");

DB::query("CREATE TABLE confirmation_codes(
  id BINARY(64) not null,
  created INT,
  email varchar(128) unique not null,
  code SMALLINT,
  confirmed BOOLEAN,
  primary key(id)
)");

DB::query("CREATE TABLE roles(
  id BINARY(64) not null,
  created date not null,
  name varchar(128) unique not null,
  primary key(id)
)");

DB::query("INSERT INTO roles (id, name, created) VALUES (UUID(), 'ADMIN', CURDATE())");
DB::query("INSERT INTO roles (id, name, created) VALUES (UUID(), 'SUPER', CURDATE())");
DB::query("INSERT INTO roles (id, name, created) VALUES (UUID(), 'USER', CURDATE())");
DB::query("INSERT INTO roles (id, name, created) VALUES (UUID(), 'OBSERVER', CURDATE())");

DB::query("CREATE TABLE user_role (
  user_id BINARY(64) unique not null,

  role_id BINARY(64) not null,

  CONSTRAINT `fk_user_role_user_id`
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_user_role_role_id`
    FOREIGN KEY (role_id) REFERENCES roles (id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
)");

DB::query("CREATE TABLE user_account (
  user_id BINARY(64) unique not null,
  account_id BINARY(64) not null,

  CONSTRAINT `fk_user_account_user_id`
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_user_account_account_id`
    FOREIGN KEY (account_id) REFERENCES accounts (id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
)");
