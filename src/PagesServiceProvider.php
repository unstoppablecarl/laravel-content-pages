<?php

namespace UnstoppableCarl\Pages;

use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use UnstoppableCarl\Pages\Contracts\PageRepository as PageRepoContract;
use UnstoppableCarl\Pages\Contracts\PageRouteBinder as PageRouteBinderContract;
use UnstoppableCarl\Pages\Exceptions\PageRepositoryNotBoundException;
use UnstoppableCarl\Pages\Exceptions\PageRouterNotFoundException;

class PagesServiceProvider extends ServiceProvider {

    protected $configKey = 'pages';

    public function register() {
        $this->registerConfig();
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(Router $router) {

        if(
            $this->packageConfig('enabled') &&
            !$this->app->routesAreCached()
        ) {

            $this->bootRoutes($router);
        }
    }

    /**
     * Registers and merges config file
     */
    protected function registerConfig() {
        $key      = $this->configKey; // 'config-name'
        $fileName = $key . '.php'; // 'config-name.php'
        $filePath = __DIR__ . '/../config/' . $fileName;

        $this->publishes([
            $filePath => config_path($fileName),
        ]);

        $this->mergeConfigFrom($filePath, $key);
    }

    protected function bootRoutes(Router $router) {

        $routeData = $this->getPageRepository()->getRouteData();

        foreach($routeData as $page) {

            $pageId          = Arr::get($page, 'id');
            $path            = Arr::get($page, 'path');
            $pageType        = Arr::get($page, 'page_type');
            $PageRouterClass = $this->packageConfig('page_types.' . $pageType . '.page_router');
            $pageRouter      = $this->getPageRouter($PageRouterClass, $pageId, $path);

            if(!$pageRouter) {
                continue;
            }

            $this->bindPageRouteGroup($pageRouter, $router, $pageId, $path);

        }
    }

    /**
     * Get the PageRepository implementation. Throw exception if not bound.
     * @return PageRepoContract
     * @throws PageRepositoryNotBoundException
     */
    protected function getPageRepository(){
        if(!$this->app->bound(PageRepoContract::class)){
            throw new PageRepositoryNotBoundException();
        }

        return $this->app->make(PageRepoContract::class);
    }

    /**
     * Get the page router instance. If it is not found throw an exception,
     * or skip if config set to ignore.
     * @param string $PageRouterClass
     * @param int    $pageId
     * @param string $path
     * @return bool|PageRouteBinderContract
     * @throws PageRouterNotFoundException
     */
    protected function getPageRouter($PageRouterClass, $pageId, $path) {
        $ignoreClassErrors = $this->packageConfig('ignore_page_router_class_errors');

        if(!class_exists($PageRouterClass) && !isset($this->app[$PageRouterClass])) {
            if($ignoreClassErrors) {
                return false;
            }
            else {
                throw new PageRouterNotFoundException($PageRouterClass, $pageId, $path);
            }
        }

        $pageRouter = $this->app->make($PageRouterClass);

        $implements = class_implements($pageRouter, PageRouteBinderContract::class);
        if(!$implements && $ignoreClassErrors) {
            return false;
        }

        return $pageRouter;
    }

    /**
     * This function is used to enforce the PageRouteBinderContract
     * @param PageRouteBinderContract $pageRouter
     * @param Router             $router
     * @param int                $pageId
     * @param string             $path
     */
    protected function bindPageRouteGroup(PageRouteBinderContract $pageRouter, Router $router, $pageId, $path) {
        $pageRouter->bindPageRouteGroup($router, $pageId, $path);
    }

    /**
     * Get config value from config/pages.php
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    protected function packageConfig($key = null, $default = null) {
        return $this->app['config']->get($this->configKey . '.' . $key, $default);
    }

}
