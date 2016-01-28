<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | If true, page routes will not be mapped to the router.
    | When first installing this must be false to avoid loading page routers
    | from db data yet to be migrated.
    |
    */

    'enabled' => env('PAGES_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Ignore Page Router Class Errors
    |--------------------------------------------------------------------------
    |
    | If true, when loading page router classes, any page router class
    | not found or not implementing the correct interface will be when binding
    | routes. The page with the broken class will then respond with a 404
    | instead of an error.
    | Usually set to true for production.
    |
    */

    'ignore_page_router_class_errors' => env('PAGES_IGNORE_CLASS_ERRORS', false),

    /*
    |--------------------------------------------------------------------------
    | Page Model Request Key
    |--------------------------------------------------------------------------
    |
    | Key used to get page model from request via $request->get($key);
    | The page model is assigned to the request by the PageInjector middleware.
    |
    */

    'page_model_request_key' => 'page_model',

    /*
    |--------------------------------------------------------------------------
    | Page Repository Class
    |--------------------------------------------------------------------------
    |
    | Class used when accessing page data. Must implement
    | UnstoppableCarl\Pages\Contracts\PageRepository interface.
    */

    'page_repository_class' => '',

    /*
    |--------------------------------------------------------------------------
    | Registered page types
    |--------------------------------------------------------------------------
    |
    | Key used to get page model from request via $request->get($key);
    | Request key page model is assigned by the PageInjector middleware
    |
    */

    'page_types' => [

        /* example */

//        'basic' => [
//            'page_router' => \App\PageRouters\Basic::class,
//            'admin_controller' => \App\Http\Controllers\Admin\PageType\Basic::class,
//        ],
//
//        'articles' => [
//            'page_router'      => \App\PageRouters\Articles::class,
//            'admin_controller' => \App\Http\Controllers\Admin\PageType\Articles::class,
//        ]

    ],
];
