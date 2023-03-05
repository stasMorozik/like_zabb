<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\ConfirmationCode\Services;

use Core;
use AMQPAdapters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Creating
{
  private Core\ConfirmationCode\UseCases\Creating $_creating_use_case;
  private AMQPAdapters\Logger $_logger;

  public function __construct(
    Core\ConfirmationCode\UseCases\Creating $creating_use_case,
    AMQPAdapters\Logger $logger
  )
  {
    $this->_creating_use_case = $creating_use_case;
    $this->_logger = $logger;
  }

  public function create(array $args): JsonResponse
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_creating_use_case->create($args);

      if ($result !== true) {
        $this->_logger->info([
          'message' => $result->getMessage(),
          'payload' => $args
        ]);

        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      $this->_logger->info([
        'message' => 'Success created confirmation code',
        'payload' => $args
      ]);

      return $resp->setStatusCode(200)->setData(true);
    } catch(Exception $e) {
      $this->_logger->warn([
        'message' => $e->getMessage(),
        'payload' => $args
      ]);

      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}
