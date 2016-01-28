
# Pages WARNING: Experemental Beta

This is an experemental package. I am still not sure if this idea is brilliant or insane.

See example: https://github.com/unstoppablecarl/laravel-content-pages-example

## Installation

Add the package via composer:

Add to `composer.json`
```
"require": {
    "unstoppablecarl/laravel-content-pages": "dev-master"
},
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/unstoppablecarl/laravel-content-pages"
    }
  ],
  "minimum-stability": "dev"
```

Add the following line to the `providers` key within your `config/app.php` file:

```php
UnstoppableCarl\Pages\PagesServiceProvider::class
```

Publish config

```php
php artisan vendor:publish
```

## The Idea

### Purpose:

 - Create a way to manage content page routes from a datasource mapping to page routers that determine route behavior
 - This would allow the creation of a admin control panel to manage content page routes keeping other related logic intact
 - Allow creation and editing of content pages including paths from a control panel

### Requirements:

 - Allow a Page Router to map sub routes to a Page's path ex: Page with path `/foo` can be assigned to a Page Router that adds one or more sub routes to its path: `/foo` `/foo/{bar}` `/foo/baz` etc.
 - A Page's path and Page Router class is stored in db
 - Must be able to cache routes as normal

### Example:

A **Page** with path `industry/articles` is mapped to an **Articles Page Router**.

The **Articles Page Router** maps 2 "sub" routes:
 - `/` to `Articles@all`
 -  `/{article_id}` to `Articles@single`

The path of the **Page** is used as a prefix to these routes resulting in mapping

 - `/industry/articles/` to `Articles@all`
 - `/industry/articles/{article_id}` to `Articles@single`

## Usage

### Create and bind PageRepository Implementation

`app/Repositories/PageRepository.php`

```php
<?php

namespace App\Repositories;

use UnstoppableCarl\Pages\Contracts\PageRepository as PageRepoContract;

class PageRepository implements PageRepoContract {

    protected $exampleData = [
        '1' => [
            // required by contract
            'id'        => 1,
            'path'      => 'industry/news',
            'page_type' => 'articles',

            // not required by contract
            'content' => 'this is some content',
        ],
    ];

    public function findById($id) {
        return $this->exampleData[$id];
    }

    public function getRouteData() {
        return $this->exampleData;
    }
}

```

Bind it in the `app/Providers/AppServiceProvider` `register` function:

```php
// ...
public function register() {

    // add this line
    $this->app->bind(\UnstoppableCarl\Pages\Contracts\PageRepository::class, \App\Repositories\PageRepository::class);

}
```

### Make a Page Controller

`app\Http\Controllers\Articles.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Articles extends Controller {

    public function all(Request $request){
        // model of Page mapped to use this controller
        $page = $request->get('page_model');
        $out = 'Articles List';
        $out .= '<br>';
        $out .= $page['content'];

        return $out;
    }

    public function single(Request $request) {
        // article from route
        $article = $request->route('article');
        // model of Page mapped to use this controller
        $page = $request->get('page_model');

        $out = 'Single Article';
        $out .= '<br>';
        $out .= $page['content'];
        $out .= '<br>';
        $out .= $article;

        return $out;
    }
}
```

### Make a PageRouteBinder

`app\PageRouteBinders\Articles.php`

```php
<?php

namespace App\PageRouteBinders;

use Illuminate\Routing\Router;
use UnstoppableCarl\Pages\PageRouteBinder;

class Articles extends PageRouteBinder {

    protected function bindPageRoutes(Router $router) {
        $router->get('/', 'Articles@all');
        $router->get('/{article}', 'Articles@single');
    }

}
```

### Configure page types in `config/pages.php`

Add the following to the `page_types` key:

```php
'page_types' => [
    'articles' => [
        'page_router' => \App\PageRouteBinders\Articles::class,
    ]
],
```

### Enable page routes in  `config/pages.php`

**WARNING:** Enabling the package will bind additional routes. If this package is not configured correctly the router may not work. This config setting determines if page routes are bound or not.

```php
    // set to true
    'enabled' => env('PAGES_ENABLED', true),
```

### Result

```
/news/articles = 'Articles@all'
/news/articles/article-slug = 'Articles@single'
```

If the path of the page is changed to `industry/news/articles`, routes would change to:

```
/industry/news/articles = 'Articles@all'
/industry/news/articles/article-slug = 'Articles@single'
```

You should now be able to see a list of all routes by doing:

`php artisan route:list`
