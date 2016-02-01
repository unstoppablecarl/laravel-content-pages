<?php

namespace UnstoppableCarl\Pages\Contracts;

interface PageRouteNamer {

    public function prefix();

    public function pageRouteGroupName($pageId);

    public function pageRouteName($page, $action);

}
