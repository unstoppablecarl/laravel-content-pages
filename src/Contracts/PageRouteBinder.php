<?php


namespace UnstoppableCarl\Pages\Contracts;

use Illuminate\Routing\Router;

interface PageRouteBinder {

    /**
     * Bind the page route group with group attributes.
     * @param Router $router
     * @param int    $pageId
     * @param string $path
     */
    public function bindPageRouteGroup(Router $router, $pageId, $path);
}
