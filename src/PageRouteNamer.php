<?php

namespace UnstoppableCarl\Pages;

use UnstoppableCarl\Pages\Contracts\PageRouteNamer as PageRouteNamerContract;
use App\Models\Page;

class PageRouteNamer implements PageRouteNamerContract {

    protected $prefix = 'page_id_';

    public function prefix() {
        return $this->prefix;
    }

    public function pageRouteGroupName($pageId) {
        return $this->prefix . $pageId . '_';
    }

    public function pageRouteName($page, $action) {

        if(
            !is_numeric($page) &&
            isset($page['id'])
        ) {
            $pageId = $page['id'];
        }
        else {
            $pageId = $page;
        }

        return $this->pageRouteGroupName($pageId) . $action;

    }

}
