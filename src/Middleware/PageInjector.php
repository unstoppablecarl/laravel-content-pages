<?php

namespace UnstoppableCarl\Pages\Middleware;

use Closure;
use UnstoppableCarl\Pages\Contracts\PageRepository;

/**
 * Injects page model into request so that it can be accessed by controller.
 */
class PageInjector {

    protected $pageRepository;

    public function __construct(PageRepository $pageRepository) {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next, $key, $pageId) {

        $page = $this->pageRepository->findById($pageId);

        if(!$page) {
            abort(404);
        }

        $request->route()->setParameter($key, $page);
        $request->merge([$key => $page]);

        return $next($request);
    }

}
