<?php declare(strict_types=1);

namespace Apps\RestApi\User\Controller;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class Controller extends AbstractController
{
  #[Route('/user/authenticate', name: 'authenticate', methods: 'POST')]
  public function authenticate(
    Apps\RestApi\User\Services\Authentication $_authentication_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_authentication_service->auth(
      $params->{'email'}, $params->{'password'}
    );
  }
}
