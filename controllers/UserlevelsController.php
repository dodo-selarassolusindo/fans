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

class UserlevelsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/userlevelslist[/{userlevelid}]", [PermissionMiddleware::class], "list.userlevels")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserlevelsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/userlevelsadd[/{userlevelid}]", [PermissionMiddleware::class], "add.userlevels")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserlevelsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/userlevelsview[/{userlevelid}]", [PermissionMiddleware::class], "view.userlevels")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserlevelsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/userlevelsedit[/{userlevelid}]", [PermissionMiddleware::class], "edit.userlevels")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserlevelsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/userlevelsdelete[/{userlevelid}]", [PermissionMiddleware::class], "delete.userlevels")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserlevelsDelete");
    }
}
