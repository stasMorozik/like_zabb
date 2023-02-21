<?php declare(strict_types=1);

namespace Core\Session;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;

class Entity extends Core\Common\Entity
{
  protected string $access_token;
  protected string $refresh_token;

  protected function __construct(
    string $id,
    DateTime $created,
    $access_token,
    $refresh_token
  )
  {
    $this->access_token = $access_token;
    $this->refresh_token = $refresh_token;
    parent::__construct($id, $created);
  }

  public static function new(array $args): Core\Common\Errors\Domain | Entity
  {
    $keys = ['access_token_salt', 'refresh_token_salt', 'id'];

    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new Core\Common\Errors\Domain('Invalid arguments');
      }
    }

    $payload = [
      "iss" => $args['id'],
      "iat" => time(),
      "exp" => time() + 900
    ];

    $access_token = JWT::encode($payload, $args['access_token_salt'], 'HS256');

    $payload = [
      "iss" => $args['id'],
      "iat" => time(),
      "exp" => time() + 86400
    ];

    $refresh_token = JWT::encode($payload, $args['refresh_token_salt'], 'HS256');

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $access_token,
      $refresh_token
    );
  }

  public function getAccessToken(): string
  {
    return $this->access_token;
  }

  public function getRefreshToken(): string
  {
    return $this->refresh_token;
  }

  public static function decode(array $args): Core\Common\Errors\Domain | string
  {
    if (!isset($args['access_token_salt'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (!isset($args['access_token'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    try {
      $payload = JWT::decode($args['access_token'], new Key($args['access_token_salt'], 'HS256'));
      return $payload->{'iss'};
    } catch(InvalidArgumentException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (DomainException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (SignatureInvalidException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (ExpiredException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (UnexpectedValueException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    }
  }

  public static function refresh(array $args)
  {
    if (!isset($args['access_token_salt'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (!isset($args['refresh_token_salt'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (!isset($args['refresh_token'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    try {
      $payload = JWT::decode($args['refresh_token'], new Key($args['refresh_token_salt'], 'HS256'));
      return Core\Session\Entity::new([
        'access_token_salt' => $args['access_token_salt'],
        'refresh_token_salt' => $args['refresh_token_salt'],
        'id' => $payload->{'iss'}
      ]);
    } catch(InvalidArgumentException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (DomainException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (SignatureInvalidException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (ExpiredException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    } catch (UnexpectedValueException $e) {
      //Some custom message
      return new Core\Common\Errors\Domain('Invalid token');
    }
  }
}
