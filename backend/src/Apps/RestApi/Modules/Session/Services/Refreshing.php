<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\Session\Services;

use Core;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Refreshing
{
  private Core\Session\UseCases\Refreshing $_refreshing_use_case;

  public function __construct(
    Core\Session\UseCases\Refreshing $refreshing_use_case
  )
  {
    $this->_refreshing_use_case = $refreshing_use_case;
  }

  public function refresh(array $args)
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_refreshing_use_case->refresh($args);

      if ($result instanceof Core\Common\Errors\Unauthorized) {
        return $resp->setStatusCode(401)->setData(["message" => $result->getMessage()]);
      }

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
