<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\Session\Services;

use Core;
use AMQPAdapters;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class Refreshing
{
  private RequestStack $_request_stack;
  private Core\Session\UseCases\Refreshing $_refreshing_use_case;
  private AMQPAdapters\Logger $_logger;

  public function __construct(
    Core\Session\UseCases\Refreshing $refreshing_use_case,
    AMQPAdapters\Logger $logger,
    RequestStack $request_stack
  )
  {
    $this->_refreshing_use_case = $refreshing_use_case;
    $this->_logger = $logger;
    $this->_request_stack = $request_stack;
  }

  public function refresh()
  {
    $resp = new JsonResponse();

    try {
      $session = $this->_request_stack->getSession();

      $result = $this->_refreshing_use_case->refresh([
        'access_token' => $session->get('access_token'),
        'refresh_token' => $session->get('refresh_token')
      ]);

      if ($result instanceof Core\Common\Errors\Unauthorized) {
        $this->_logger->info([
          'message' => $result->getMessage(),
          'payload' => [
            'access_token' => $session->get('access_token'),
            'refresh_token' => $session->get('refresh_token')
          ]
        ]);

        return $resp->setStatusCode(401)->setData(["message" => $result->getMessage()]);
      }

      if (($result instanceof Core\Session\Entity) == false) {
        $this->_logger->info([
          'message' => $result->getMessage(),
          'payload' => [
            'access_token' => $session->get('access_token'),
            'refresh_token' => $session->get('refresh_token')
          ]
        ]);

        return $resp->setStatusCode(400)->setData(["message" => $result->getMessage()]);
      }

      $session->set('access_token', $result->getAccessToken());
      $session->set('refresh_token', $result->getRefreshToken());

      $this->_logger->info([
        'message' => 'Success updated session',
        'payload' => [
          'access_token' => $session->get('access_token'),
          'refresh_token' => $session->get('refresh_token')
        ]
      ]);

      return $resp->setStatusCode(200)->setData(true);
    } catch(Exception $e) {
      $this->_logger->warn([
        'message' => $e->getMessage(),
        'payload' => [
          'access_token' => $session->get('access_token'),
          'refresh_token' => $session->get('refresh_token')
        ]
      ]);

      return $resp->setStatusCode(500)->setData(["message" => "Something went wrong"]);
    }
  }
}
