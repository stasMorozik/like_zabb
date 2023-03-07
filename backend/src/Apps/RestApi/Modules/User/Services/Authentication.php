<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\User\Services;

use Core;
use AMQPAdapters;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class Authentication
{
  private RequestStack $_request_stack;
  private Core\User\UseCases\Authentication $_authentication_use_case;
  private AMQPAdapters\Logger $_logger;

  public function __construct(
    Core\User\UseCases\Authentication $authentication_use_case,
    AMQPAdapters\Logger $logger,
    RequestStack $request_stack
  )
  {
    $this->_authentication_use_case = $authentication_use_case;
    $this->_logger = $logger;
    $this->_request_stack = $request_stack;
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

      $session = $this->_request_stack->getSession();

      $session->set('access_token', $result->getAccessToken());
      $session->set('refresh_token', $result->getRefreshToken());

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
