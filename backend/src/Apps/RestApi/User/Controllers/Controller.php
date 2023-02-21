<?php declare(strict_types=1);

namespace Apps\RestApi\User\Controller;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class Controller extends AbstractController
{
  #[Route('/user/auth', name: 'authenticate', methods: 'POST')]
  public function authenticate(
    Apps\RestApi\User\Services\Authentication $_authentication_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_authentication_service->auth([
      'email' => $params->{'email'},
      'password' => $params->{'password'}
    ]);
  }

  #[Route('/user', name: 'authorization', methods: 'GET')]
  public function authorization(
    Apps\RestApi\User\Services\Authorization $_authorization_service,
    Request $request
  ): JsonResponse
  {
    return $_authorization_service->auth([
      'access_token' => isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ''
    ]);
  }
}
