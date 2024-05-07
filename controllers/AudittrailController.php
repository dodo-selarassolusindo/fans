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

class AudittrailController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/audittraillist[/{Id}]", [PermissionMiddleware::class], "list.audittrail")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/audittrailadd[/{Id}]", [PermissionMiddleware::class], "add.audittrail")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/audittrailview[/{Id}]", [PermissionMiddleware::class], "view.audittrail")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/audittrailedit[/{Id}]", [PermissionMiddleware::class], "edit.audittrail")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/audittraildelete[/{Id}]", [PermissionMiddleware::class], "delete.audittrail")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailDelete");
    }
}
