<?php declare(strict_types=1);

namespace RestApi\Account\Services;

use Core;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Creating 
{
  private Core\Account\UseCases\Creating $_creating_use_case;

  public function __construct(
    Core\Account\UseCases\Creating $creating_use_case
  )
  {
    $this->_creating_use_case = $creating_use_case; 
  }

  public function create(
    ?string $email,
    ?string $password,
    ?string $name
  ): JsonResponse
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_creating_use_case->create($email, $password, $name);

      if ($result !== true) {
        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      return $resp->setStatusCode(200)->setData(true);
    } catch(Exception $_) {
      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}