<?php declare(strict_types=1);

namespace Apps\RestApi\ConfirmationCode\Controller;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends AbstractController
{
  #[Route('/confirmation-code', name: 'create', methods: 'POST')]
  public function create(
    Apps\RestApi\ConfirmationCode\Services\Creating $_creating_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_creating_service->create(
      $params->{'email'}
    );
  }

  #[Route('/confirmation-code', name: 'confirm', methods: 'PUT')]
  public function confirm(
    RestApi\ConfirmationCode\Services\Confirming $_confirming_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_confirming_service->confirm(
      $params->{'email'},
      (int) $params->{'code'}
    );
  }
}
