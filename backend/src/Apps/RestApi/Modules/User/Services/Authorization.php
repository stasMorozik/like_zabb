<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\User\Services;

use Core;
use AMQPAdapters;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class Authorization
{
  private RequestStack $_request_stack;
  private Core\User\UseCases\Authorization $_authorization_use_case;
  private AMQPAdapters\Logger $_logger;

  public function __construct(
    Core\User\UseCases\Authorization $authorization_use_case,
    AMQPAdapters\Logger $logger,
    RequestStack $request_stack
  )
  {
    $this->_authorization_use_case = $authorization_use_case;
    $this->_logger = $logger;
    $this->_request_stack = $request_stack;
  }

  public function auth()
  {
    $resp = new JsonResponse();

    try {
      $session = $this->_request_stack->getSession();

      $result = $this->_authorization_use_case->auth([
        'access_token' => $session->get('access_token')
      ]);

      if (($result instanceof Core\User\Entity) == false) {
        $this->_logger->info([
          'message' => $result->getMessage(),
          'payload' => [
            'access_token' => $session->get('access_token')
          ]
        ]);

        return $resp->setStatusCode(401)->setData(["message" => $result->getMessage()]);
      }

      $this->_logger->info([
        'message' => 'Success user authorized',
        'payload' => [
          'access_token' => $session->get('access_token')
        ]
      ]);

      return $resp->setStatusCode(200)->setData([
        "name" => $result->getName()->getValue(),
        "email" => $result->getEmail()->getValue(),
        "role" => [
          "name" => $result->getRole()->getName()->getValue(),
        ],
        "account" => [
          "email" => $result->getAccount()->getEmail()->getValue()
        ]
      ]);
    } catch(Exception $e) {
      $this->_logger->warn([
        'message' => $e->getMessage(),
        'payload' => [
          'access_token' => $session->get('access_token')
        ]
      ]);

      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}
