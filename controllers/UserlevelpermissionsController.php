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

class UserlevelpermissionsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/userlevelpermissionslist[/{keys:.*}]", [PermissionMiddleware::class], "list.userlevelpermissions")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "UserlevelpermissionsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/userlevelpermissionsadd[/{keys:.*}]", [PermissionMiddleware::class], "add.userlevelpermissions")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "UserlevelpermissionsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/userlevelpermissionsview[/{keys:.*}]", [PermissionMiddleware::class], "view.userlevelpermissions")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "UserlevelpermissionsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/userlevelpermissionsedit[/{keys:.*}]", [PermissionMiddleware::class], "edit.userlevelpermissions")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "UserlevelpermissionsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/userlevelpermissionsdelete[/{keys:.*}]", [PermissionMiddleware::class], "delete.userlevelpermissions")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "UserlevelpermissionsDelete");
    }

    // Get keys as associative array
    protected function getKeyParams($args)
    {
        global $RouteValues;
        if (array_key_exists("keys", $args)) {
            $sep = Container("userlevelpermissions")->RouteCompositeKeySeparator;
            $keys = explode($sep, $args["keys"]);
            if (count($keys) == 2) {
                $keyArgs = array_combine(["userlevelid","_tablename"], $keys);
                $RouteValues = array_merge(Route(), $keyArgs);
                $args = array_merge($args, $keyArgs);
            }
        }
        return $args;
    }
}
