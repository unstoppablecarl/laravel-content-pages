<?php


namespace UnstoppableCarl\Pages\Exceptions;


use Exception;

class PageRouterNotFoundException extends Exception {

    public function __construct($PageTypeClass, $pageId = null, $pagePath = null) {

        $msg = 'Page Type Class: "' . $PageTypeClass . '"';

        if($pageId || $pagePath) {
            $msg .= 'of page: ';
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
