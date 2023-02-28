<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\User\Services;

use Core;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Authentication
{
  private Core\User\UseCases\Authentication $_authentication_use_case;

  public function __construct(
    Core\User\UseCases\Authentication $authentication_use_case
  )
  {
    $this->_authentication_use_case = $authentication_use_case;
  }

  public function auth(array $args)
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_authentication_use_case->auth($args);

      if (($result instanceof Core\Session\Entity) == false) {
        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      session_start();

      $_SESSION["access_token"] = $result->getAccessToken();
      $_SESSION["refresh_token"] = $result->getRefreshToken();

      return $resp->setStatusCode(200)->setData(true);

    } catch(Exception $_) {
      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}
