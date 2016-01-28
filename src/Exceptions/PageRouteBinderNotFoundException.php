<?php


namespace UnstoppableCarl\Pages\Exceptions;


use Exception;

class PageRouteBinderNotFoundException extends Exception {

    public function __construct($PageRouteBinderClass, $pageType = null, $pageId = null, $pagePath = null) {

        $msg = 'Page Route Binder Class: "' . $PageRouteBinderClass . '"';


        if($pageId || $pagePath || $pageType) {
            $msg .= 'of page where: ';
            if($pageType) {
                $msg .= ', page_type = "' . $pageType . '"';
            }
            if($pageId) {
                $msg .= 'id = "' . $pageId . '"';
            }
            if($pagePath) {
                $msg .= ', path = "' . $pagePath . '"';
            }
        }

        $msg .= ' Not Found';

        parent::__construct($msg);
    }
}
