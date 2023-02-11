<?php declare(strict_types=1);

namespace Core\Session;

use Core;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;

class Entity
{
  public string $access_token;
  public string $refresh_token;

  public function __construct(
    string $access_token_salt,
    string $refresh_token_salt,
    string $id
  )
  {
    $payload = [
      "iss" => $id,
      "iat" => time(),
      "exp" => time() + 900
    ];

    $this->access_token = JWT::encode($payload, $access_token_salt, 'HS256');

    $payload = [
      "iss" => $id,
      "iat" => time(),
      "exp" => time() + 86400
    ];

    $this->refresh_token = JWT::encode($payload, $refresh_token_salt, 'HS256');
  }

  public static function decode(string $access_token_salt, ?string $access_token): Core\Common\Errors\Domain | string
  {
    try {
      $payload = JWT::decode($access_token, new Key($access_token_salt, 'HS256'));
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

  public static function refresh(string $access_token_salt, string $refresh_token_salt, ?string $refresh_token)
  {
    try {
      $payload = JWT::decode($refresh_token, new Key($refresh_token_salt, 'HS256'));
      return new Core\Session\Entity(
        $access_token_salt,
        $refresh_token_salt,
        $payload->{'iss'}
      );
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
