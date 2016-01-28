<?php

namespace UnstoppableCarl\Pages\Middleware;

use Closure;

/**
 * Sets the admin page controller used to create a new page of given page type.
 * Whatever controller this middleware is attached to will be replaced by the appropriate
 * admin page type controller based on the page type of the page being created.
 */
class AdminPageControllerCreate extends AdminPageController {

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param string                    $method method to be used on page controller
     * @return mixed
     */
    public function handle($request, Closure $next, $method = null) {

        $pageType = $request->route('page_type');

        $this->setPageTypeController($request, $pageType, $method);

        return $next($request);
    }

}
