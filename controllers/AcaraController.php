<?php

namespace PHPMaker2024\prj_fans;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\prj_fans\Attributes\Delete;
use PHPMaker2024\prj_fans\Attributes\Get;
use PHPMaker2024\prj_fans\Attributes\Map;
use PHPMaker2024\prj_fans\Attributes\Options;
use PHPMaker2024\prj_fans\Attributes\Patch;
use PHPMaker2024\prj_fans\Attributes\Post;
use PHPMaker2024\prj_fans\Attributes\Put;

class AcaraController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/acaralist[/{AcaraID}]", [PermissionMiddleware::class], "list.acara")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AcaraList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/acaraadd[/{AcaraID}]", [PermissionMiddleware::class], "add.acara")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AcaraAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/acaraview[/{AcaraID}]", [PermissionMiddleware::class], "view.acara")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AcaraView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/acaraedit[/{AcaraID}]", [PermissionMiddleware::class], "edit.acara")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AcaraEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/acaradelete[/{AcaraID}]", [PermissionMiddleware::class], "delete.acara")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AcaraDelete");
    }
}
