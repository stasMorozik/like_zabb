<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\User\Services;

use Core;
use AMQPAdapters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Authorization
{
  private Core\User\UseCases\Authorization $_authorization_use_case;
  private AMQPAdapters\Logger $_logger;

  public function __construct(
    Core\User\UseCases\Authorization $authorization_use_case,
    AMQPAdapters\Logger $logger
  )
  {
    $this->_authorization_use_case = $authorization_use_case;
    $this->_logger = $logger;
  }

  public function auth(array $args)
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_authorization_use_case->auth($args);

      if (($result instanceof Core\User\Entity) == false) {
        $this->_logger->info([
          'message' => $result->getMessage(),
          'payload' => $args
        ]);

        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      $this->_logger->info([
        'message' => 'Success user authorized',
        'payload' => $args
      ]);

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
    } catch(Exception $e) {
      $this->_logger->warn([
        'message' => $e->getMessage(),
        'payload' => $args
      ]);

      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}
