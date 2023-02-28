<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\Sensor\Controllers;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class BaseController extends AbstractController
{
  public function create(
    Apps\RestApi\Modules\Sensor\Services\Creating $_creating_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_creating_service->create(array_merge([
      'access_token' => isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ''
    ], $params));
  }
}
