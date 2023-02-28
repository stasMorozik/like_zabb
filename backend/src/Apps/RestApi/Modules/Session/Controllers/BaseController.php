<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\Session\Controllers;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class BaseController extends AbstractController
{
  public function refresh(
    Apps\RestApi\Modules\Session\Services\Refreshing $_refreshing_service,
    Request $request
  ): JsonResponse
  {
    return $_refreshing_service->refresh([
      'refresh_token' => isset($_SESSION["refresh_token"]) ? $_SESSION["refresh_token"] : ''
    ]);
  }

  public function quit(
    Request $request
  ): JsonResponse
  {
    unset($_SESSION["refresh_token"]);
    unset($_SESSION["access_token"]);

    $resp = new JsonResponse();

    return $resp->setStatusCode(200)->setData(true);
  }
}
