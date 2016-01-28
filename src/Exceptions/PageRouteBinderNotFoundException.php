<?php


namespace UnstoppableCarl\Pages\Exceptions;


use Exception;

class PageRouteBinderNotFoundException extends Exception {

    public function __construct($PageRouteBinderClass, $pageType, $pageId, $pagePath) {

        $msg = 'Page Route Binder Class: "%s" Not Found.
When trying to bind page with: page_type = "%s", id = "%s", path = "%s"';
        $msg = sprintf($msg, $PageRouteBinderClass, $pageType, $pageId, $pagePath);

        parent::__construct($msg);
    }
}
