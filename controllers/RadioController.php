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

class RadioController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/radiolist[/{RadioID}]", [PermissionMiddleware::class], "list.radio")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RadioList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/radioadd[/{RadioID}]", [PermissionMiddleware::class], "add.radio")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RadioAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/radioview[/{RadioID}]", [PermissionMiddleware::class], "view.radio")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RadioView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/radioedit[/{RadioID}]", [PermissionMiddleware::class], "edit.radio")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RadioEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/radiodelete[/{RadioID}]", [PermissionMiddleware::class], "delete.radio")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RadioDelete");
    }
}
