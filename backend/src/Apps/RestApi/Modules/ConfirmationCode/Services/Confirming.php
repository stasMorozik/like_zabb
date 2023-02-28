<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\ConfirmationCode\Services;

use Core;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Confirming
{
  private Core\ConfirmationCode\UseCases\Confirming $_confirming_use_case;

  public function __construct(
    Core\ConfirmationCode\UseCases\Confirming $confirming_use_case
  )
  {
    $this->_confirming_use_case = $confirming_use_case;
  }

  public function confirm(array $args): JsonResponse
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_confirming_use_case->confirm($args);

      if ($result !== true) {
        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      return $resp->setStatusCode(200)->setData(true);
    } catch(Exception $_) {
      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}
