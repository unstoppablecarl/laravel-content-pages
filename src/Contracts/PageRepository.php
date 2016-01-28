<?php

namespace UnstoppableCarl\Pages\Contracts;

interface PageRepository {

    /**
     * @param int $id
     * @return mixed model or array
     */
    public function findById($id);

    /**
     * must return array of page data arrays
     * [
     *   ['id' => null, 'path' => null, 'page_type' => null],
     *   ['id' => null, 'path' => null, 'page_type' => null],
     *   // ...
     * ]
     * @return mixed
     */
    public function getRouteData();
}
