<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\Session\Services;

use Core;
use AMQPAdapters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Refreshing
{
  private Core\Session\UseCases\Refreshing $_refreshing_use_case;
  private AMQPAdapters\Logger $_logger;

  public function __construct(
    Core\Session\UseCases\Refreshing $refreshing_use_case,
    AMQPAdapters\Logger $logger
  )
  {
    $this->_refreshing_use_case = $refreshing_use_case;
    $this->_logger = $logger;
  }

  public function refresh(array $args)
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_refreshing_use_case->refresh($args);

      if ($result instanceof Core\Common\Errors\Unauthorized) {
        $this->_logger->info([
          'message' => $result->getMessage(),
          'payload' => $args
        ]);

        return $resp->setStatusCode(401)->setData(["message" => $result->getMessage()]);
      }

      if (($result instanceof Core\Session\Entity) == false) {
        $this->_logger->info([
          'message' => $result->getMessage(),
          'payload' => $args
        ]);

        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      session_start();

      $_SESSION["access_token"] = $result->getAccessToken();
      $_SESSION["refresh_token"] = $result->getRefreshToken();

      $this->_logger->info([
        'message' => 'Success updated session',
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
