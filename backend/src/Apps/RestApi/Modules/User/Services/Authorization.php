<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\User\Services;

use Core;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Authorization
{
  private Core\User\UseCases\Authorization $_authorization_use_case;

  public function __construct(
    Core\User\UseCases\Authorization $authorization_use_case
  )
  {
    $this->_authorization_use_case = $authorization_use_case;
  }

  public function auth(array $args)
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_authorization_use_case->auth($args);

      if (($result instanceof Core\User\Entity) == false) {
        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      return $resp->setStatusCode(200)->setData([
        "id" => $result->getId(),
        "name" => $result->getName()->getValue(),
        "email" => $result->getEmail()->getValue(),
        "created" => $result->getCreated(),
        "role" => [
          "id" => $result->getRole()->getId(),
          "name" => $result->getRole()->getName()->getValue(),
          "created" => $result->getRole()->getCreated()
        ]
      ]);
    } catch(Exception $_) {
      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}
