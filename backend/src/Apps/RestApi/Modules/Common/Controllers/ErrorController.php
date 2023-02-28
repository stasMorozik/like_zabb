<?php declare(strict_types=1);

namespace Apps\RestApi\Modules\Common\Controllers;

use Apps;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorController extends AbstractController
{
  public function show(): JsonResponse
  {
    $resp = new JsonResponse();
    return $resp->setStatusCode(404)->setData(["message" => 'Not found']);
  }
}
