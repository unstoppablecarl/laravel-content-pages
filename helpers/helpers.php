<?php

use UnstoppableCarl\Pages\Contracts\PageRouteNamer;

if(!function_exists('page_route')) {
    /**
     * Generate a url for a given page action
     * @param Page|int $page model or id
     * @param string   $action
     * @param array    $params
     * @param bool     $absolute
     * @return string
     * @internal param string $path
     */
    function page_route($page, $action, $params = [], $absolute = true) {

        $pageRouteNamer = app(PageRouteNamer::class);
        $pageRouteName  = $pageRouteNamer->pageRouteName($page, $action);

        return route($pageRouteName, $params, $absolute);

    }
}

