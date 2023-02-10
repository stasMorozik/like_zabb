<?php declare(strict_types=1);

namespace Apps\RestApi\Account\Controller;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends AbstractController
{
  #[Route('/account', name: 'create', methods: 'POST')]
  public function create(
    Apps\RestApi\Account\Services\Creating $_creating_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_creating_service->create(
      $params->{'email'},
      $params->{'password'},
      $params->{'name'}
    );
  }
}
