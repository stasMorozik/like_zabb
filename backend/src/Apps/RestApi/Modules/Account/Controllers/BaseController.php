<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\Account\Controllers;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
  public function create(
    Apps\RestApi\Modules\Account\Services\Creating $_creating_service,
    Request $request
  ): JsonResponse
  {
    $params = json_decode($request->getContent());

    return $_creating_service->create([
      'email' => $params->{'email'},
      'password' => $params->{'password'},
      'name' => $params->{'name'}
    ]);
  }
}
