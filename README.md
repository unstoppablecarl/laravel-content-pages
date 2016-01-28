
# Pages WARNING: Experemental Beta

This is an experemental package. I am still not sure if this idea is brilliant or insane.

## Installation

Add the package via composer:

```php
TBD
```

Then add the following line to the `providers` key within your `config/app.php` file:

```php
UnstoppableCarl\Pages\PagesServiceProvider::class
```

Then publish config

```php
php artisan vendor:publish
```

Then enable in  `config/pages.php`

```php
    // set to true
    'enabled' => env('RP_ENABLED', true),
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

The **Articles Page Router** maps 2 sub routes:
 - `/` to `Articles@all`
 -  `/{article_id}` to `Articles@single`

The path of the **Page** is used as a prefix to these routes resulting in mapping

 - `/industry/articles/` to `Articles@all`
 - `/industry/articles/{article_id}` to `Articles@single`


## Usage


### Making a Page Controller
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Articles extends Controller {

    public function all(Request $request){
        // model of Page mapped to use this controller
        $page = $request->get('page_model');
        $out = 'Articles List';
        $out .= '<br>';
        $out .= $page->content;

        return $out;
    }

    public function single(Request $request) {
        // article from route
        $article = $request->route('article');
        // model of Page mapped to use this controller
        $page = $request->get('page_model');

        $out = 'Single Article';
        $out .= '<br>';
        $out .= $page->content;
        $out .= '<br>';
        $out .= $article;

        return $out;
    }
}
```

### Making a PageRouteBinder

```php
namespace App\PageRouters;

use Illuminate\Routing\Router;
use UnstoppableCarl\Pages\PageRouteBinder;

class Articles extends PageRouteBinder {

    protected function bindPageRoutes(Router $router) {
        $router->any('/', 'Articles@all');
        $router->any('/{article}', 'Articles@single');
    }

}
```

### Mapping a Page to a Page Router

```php
use UnstoppableCarl\Pages\Models\Page;

$page = new Page();
$page->fill([
    'path' => 'news/articles',
    'page_router_class' => App\PageRouters\Articles::class
]);
```

### Result

```
/news/articles = 'Articles@all'
/news/articles/article-slug = 'Articles@single'
```

If path of page is changed to `industry/news/articles`, routes would change to:

```
/industry/news/articles = 'Articles@all'
/industry/news/articles/article-slug = 'Articles@single'
```


See example code in `/examples`
