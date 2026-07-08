<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DynamicPageController extends Controller
{

    public function handle(Request $request)
    {
        $fullPath   = trim($request->path(), '/');
        $basePrefix = "admin/dashboard/";

        if (!str_starts_with($fullPath, $basePrefix)) {
            abort(404, "Invalid route.");
        }

        $relative = substr($fullPath, strlen($basePrefix));
        $segments = array_values(array_filter(
            explode('/', $relative),
            fn($val) => $val !== ''
        ));

        if (count($segments) === 0) {
            abort(404, "Empty dynamic route.");
        }

        // Resolve controller from App\Http\Controllers\backend\dashboard\ namespace
        $controllerClass = null;
        $controllerIndex = null;

        for ($i = count($segments) - 1; $i >= 0; $i--) {
            $subFolders     = array_slice($segments, 0, $i);
            $controllerName = ucfirst($segments[$i]); // "college" → "College"
            $subPath        = $subFolders
                ? implode("\\", array_map('ucfirst', $subFolders)) . "\\"
                : "";

            $candidate = "App\\Http\\Controllers\\backend\\dashboard\\" 
                         . $subPath . $controllerName;

            if (class_exists($candidate)) {
                $controllerClass = $candidate;
                $controllerIndex = $i;
                break;
            }
        }

        if (!$controllerClass) {
            abort(404, "Controller not found for this route.");
        }

        $controller   = app($controllerClass);
        $afterController = array_slice($segments, $controllerIndex + 1);
        $method       = "index";
        $params       = [];

        if (!empty($afterController)) {
            $possibleMethod = $afterController[0];
            if (method_exists($controller, $possibleMethod)) {
                $method = $possibleMethod;
                $params = array_slice($afterController, 1);
            } else {
                $method = "index";
                $params = $afterController;
            }
        }

        if (!method_exists($controller, $method)) {
            abort(404, "Method '{$method}' not found.");
        }

        $reflection  = new \ReflectionMethod($controller, $method);
        $finalParams = [];
        $paramIndex  = 0;

        foreach ($reflection->getParameters() as $param) {
            if ($param->getType() && $param->getType()->getName() === Request::class) {
                $finalParams[$param->getName()] = $request;
            } else {
                $finalParams[$param->getName()] = $params[$paramIndex] ?? null;
                $paramIndex++;
            }
        }

        return app()->call([$controller, $method], $finalParams);
    }

}