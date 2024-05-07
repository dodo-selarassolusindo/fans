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

class FansController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/fanslist[/{FansID}]", [PermissionMiddleware::class], "list.fans")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FansList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/fansadd[/{FansID}]", [PermissionMiddleware::class], "add.fans")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FansAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/fansview[/{FansID}]", [PermissionMiddleware::class], "view.fans")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FansView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/fansedit[/{FansID}]", [PermissionMiddleware::class], "edit.fans")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FansEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/fansdelete[/{FansID}]", [PermissionMiddleware::class], "delete.fans")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FansDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/fanssearch", [PermissionMiddleware::class], "search.fans")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FansSearch");
    }

    // query
    #[Map(["GET","POST","OPTIONS"], "/fansquery", [PermissionMiddleware::class], "query.fans")]
    public function query(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FansSearch", "FansQuery");
    }
}
