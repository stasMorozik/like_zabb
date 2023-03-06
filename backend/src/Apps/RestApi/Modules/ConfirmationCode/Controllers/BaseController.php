<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\ConfirmationCode\Controllers;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
  public function create(
    Apps\RestApi\Modules\ConfirmationCode\Services\Creating $_creating_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_creating_service->create(['email' => $params->{'email'}]);
  }

  public function confirm(
    Apps\RestApi\Modules\ConfirmationCode\Services\Confirming $_confirming_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_confirming_service->confirm([
      'email' => $params->{'email'},
      'code' => (int) $params->{'code'}
    ]);
  }
}
