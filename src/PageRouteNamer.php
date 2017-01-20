<?php

namespace UnstoppableCarl\Pages;

use UnstoppableCarl\Pages\Contracts\PageRouteNamer as PageRouteNamerContract;
use App\Models\Page;

class PageRouteNamer implements PageRouteNamerContract {

    /**
     * Prefix of content page route name
     * @var string
     */
    protected $prefix = 'page_id_';

    /**
     * Public access to $this->prefix
     * @return string
     */
    public function prefix() {
        return $this->prefix;
    }

    /**
     * Name for the page route group
     * @param int|string $pageId
     * @return string
     */
    public function pageRouteGroupName($pageId) {
        return $this->prefix() . $pageId . '_';
    }

    /**
     * Name for the page route
     * @param int|Page|array $page
     * @param string $action
     * @return string
     */
    public function pageRouteName($page, $action) {

        if(is_array($page) || $page instanceof ArrayAccess) {
            $pageId = $page['id'];
        } else {
            $pageId = $page;
        }

        return $this->pageRouteGroupName($pageId) . $action;

    }

}
