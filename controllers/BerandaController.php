<?php

namespace PHPMaker2024\prj_fans;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\prj_fans\Attributes\Delete;
use PHPMaker2024\prj_fans\Attributes\Get;
use PHPMaker2024\prj_fans\Attributes\Map;
use PHPMaker2024\prj_fans\Attributes\Options;
use PHPMaker2024\prj_fans\Attributes\Patch;
use PHPMaker2024\prj_fans\Attributes\Post;
use PHPMaker2024\prj_fans\Attributes\Put;

/**
 * beranda controller
 */
class BerandaController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/beranda[/{params:.*}]", [PermissionMiddleware::class], "custom.beranda")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Beranda");
    }
}
