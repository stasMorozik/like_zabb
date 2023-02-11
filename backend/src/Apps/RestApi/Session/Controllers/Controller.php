<?php declare(strict_types=1);

namespace Apps\RestApi\Session\Controller;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class Controller extends AbstractController
{
  #[Route('/session/refresh', name: 'refresh', methods: 'GET')]
  public function refresh(
    Apps\RestApi\Session\Services\Refreshing $_refreshing_service,
    Request $request
  ): JsonResponse
  {
    return $_refreshing_service->refresh(
      isset($_SESSION["refresh_token"]) ? $_SESSION["refresh_token"] : ''
    );
  }

  #[Route('/session/quit', name: 'quit', methods: 'DELETE')]
  public function quit(
    Request $request
  ): JsonResponse
  {
    unset($_SESSION["newsession"]);

    $resp = new JsonResponse();

    return $resp->setStatusCode(200)->setData(true);
  }
}
