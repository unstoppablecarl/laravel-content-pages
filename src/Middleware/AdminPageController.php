<?php

namespace UnstoppableCarl\Pages\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Sets the admin controller used to modify an existing page of a given page type.
 * Whatever controller this middleware is attached to will be replaced by the appropriate
 * admin page type controller based on the page type of the page being modified.
 */
class AdminPageController {

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $config;

    /**
     * AdminPageController constructor.
     * @param Application $app
     * @param Repository  $config
     */
    public function __construct(Application $app, Repository $config) {
        $this->app    = $app;
        $this->config = $config->get('pages');
    }

    /**
     * Get the config value of a given page type.
     * @param string $pageType
     * @param null $key
     * @param null $default
     * @return mixed
     */
    protected function pageTypeConfig($pageType, $key = null, $default = null) {
        $key = 'page_types.' . $pageType . '.' . $key;

        return Arr::get($this->config, $key, $default);
    }

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param string                    $method method to be used on page controller (instead of using method currently set on route)
     * @return mixed
     */
    public function handle($request, Closure $next, $method = null) {

        $page     = $request->route('content_page');

        $pageType = $page->page_type;

        $this->setPageTypeController($request, $pageType, $method);

        return $next($request);
    }

    /**
     * Set the controller and method on this request based on page type.
     * @param Request $request
     * @param         $pageType
     * @param         $method
     */
    protected function setPageTypeController(Request $request, $pageType, $method) {
        $adminController = $this->pageTypeAdminControllerClass($pageType);

        if(!$adminController) {
            abort(404);
        }

        if(!$method) {
            $currentControllerAction = $request->route()->getActionName();
            $method                  = $this->methodFromString($currentControllerAction);
        }

        $controllerAction = $adminController . '@' . $method;

        $this->overrideControllerAction($request, $controllerAction);
    }

    /**
     * Sets a new controller class and action on the request.
     * @param Request $request
     * @param         $controllerAction
     */
    protected function overrideControllerAction(Request $request, $controllerAction) {

        $route = $request->route();

        $routeAction = array_merge($route->getAction(), [
            'uses'       => $controllerAction,
            'controller' => $controllerAction,
        ]);

        $route->setAction($routeAction);
    }

    /**
     * Gets admin controller class from page type.
     * @param $pageType
     * @return bool|mixed false if class cannot be found
     */
    protected function pageTypeAdminControllerClass($pageType) {
        $PageControllerClass = $this->pageTypeConfig($pageType, 'admin_controller');

        if(
            !class_exists($PageControllerClass) &&
            !isset($this->app[$PageControllerClass])
        ) {
            return false;
        }

        return $PageControllerClass;
    }

    /**
     * get method from Controller@method
     * @param string $str
     * @return bool|string returns false if $str === 'Closure'
     */
    protected function methodFromString($str) {

        if($str === 'Closure') {
            return false;
        }

        $arr = explode('@', $str);

        if(!isset($arr[1])) {
            return false;
        }

        return $arr[1];

    }

}
