<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\User\Controllers;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class BaseController extends AbstractController
{
  public function authenticate(
    Apps\RestApi\Modules\User\Services\Authentication $_authentication_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_authentication_service->auth([
      'email' => $params->{'email'},
      'password' => $params->{'password'}
    ]);
  }

  public function authorize(
    Apps\RestApi\Modules\User\Services\Authorization $_authorization_service,
    Request $request
  ): JsonResponse
  {
    return $_authorization_service->auth();
  }
}
