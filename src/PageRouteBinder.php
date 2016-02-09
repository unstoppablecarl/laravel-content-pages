<?php

namespace UnstoppableCarl\Pages;

use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use \UnstoppableCarl\Pages\Contracts\PageRouteBinder as PageRouteBinderContract;
use UnstoppableCarl\Pages\Contracts\PageRouteNamer;
use UnstoppableCarl\Pages\Middleware\PageInjector;

/**
 * Binds (non-admin) routes of a Page based on it's page type. This is primarily done in the
 * bindPageRoutes and bindPageRouteGroup methods. This is the base class used To make PageRouteBinders for page types.
 */
abstract class PageRouteBinder implements PageRouteBinderContract {

    /**
     * @var PageRouteNamer
     */
    protected $pageRouteNamer;

    /**
     * Key used to get page model from request via $request->get($key);
     * if no value set in class, use config value from 'page-types.request_page_key'
     * @var string
     */
    protected $requestPageKey;

    /**
     * Controller namespace used for all routes bound by this page type
     * @var string
     */
    protected $controllerNamespace = 'App\Http\Controllers';

    /**
     * Default middleware applied to route groups
     * @var array
     */
    protected $defaultRouteGroupMiddleware = ['web'];

    /**
     * PageRouteBinder constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config, PageRouteNamer $pageRouteNamer) {
        $this->requestPageKey = $this->requestPageKey ?: $config->get('pages.page_model_request_key', 'page_model');
        $this->pageRouteNamer = $pageRouteNamer;
    }

    /**
     * Bind the page route group with group attributes.
     * @param Router $router
     * @param int    $pageId
     * @param string $path
     */
    public function bindPageRouteGroup(Router $router, $pageId, $path) {
        $attributes = $this->routeGroupAttributes($path, $pageId);

        $router->group($attributes, function ($router) {
            $this->bindPageRoutes($router);
        });
    }

    /**
     * Binds routes for page using page path as a prefix.
     * Must at least include $router->get('/', 'Foo@bar'); for page to work.
     * @param Router $router
     */
    protected function bindPageRoutes(Router $router) {

        /**
         *  bind page routes here
         *  example:
         *  $router->get('/', 'Articles@all');
         *  $router->get('/{article}', 'Articles@single');
         */
    }

    /**
     * Route group attributes array. Sets the page path to the prefix and PageInjector middleware
     * @param string $path
     * @param int    $pageId
     * @return array
     */
    protected function routeGroupAttributes($path, $pageId) {

        return [
            'as'         => $this->pageRouteNamer->pageRouteGroupName($pageId),
            'prefix'     => $path,
            'namespace'  => $this->controllerNamespace,
            'middleware' => $this->routeGroupMiddleware($path, $pageId)
        ];
    }

    /**
     * Get route group middleware array.
     * @param string $path
     * @param int    $pageId
     * @return array
     */
    protected function routeGroupMiddleware($path, $pageId) {
        $key      = $this->requestPageKey;
        $injector = PageInjector::class . ':' . $key . ',' . $pageId;

        return array_merge($this->defaultRouteGroupMiddleware, [$injector]);
    }

}
