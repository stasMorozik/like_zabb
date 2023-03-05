<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\User\Services;

use Core;
use AMQPAdapters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class Authentication
{
  private Core\User\UseCases\Authentication $_authentication_use_case;
  private AMQPAdapters\Logger $_logger;

  public function __construct(
    Core\User\UseCases\Authentication $authentication_use_case,
    AMQPAdapters\Logger $logger
  )
  {
    $this->_authentication_use_case = $authentication_use_case;
    $this->_logger = $logger;
  }

  public function auth(array $args)
  {
    $resp = new JsonResponse();

    try {
      $result = $this->_authentication_use_case->auth($args);

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
        'message' => 'Success user authenticated',
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
