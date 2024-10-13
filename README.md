# Nano\Router

**Nano\Router** is a lightweight PHP routing library designed for simple and efficient routing in your PHP applications. It allows handling various HTTP request methods (GET, POST, PUT, DELETE, etc.), dynamic parameters, and redirects.

## Features
- Simple routing for multiple HTTP methods (`GET`, `POST`, `PUT`, `DELETE`, etc.)
- Dynamic route parameters
- Route redirection
- Custom base paths for easier routing
- Exception handling for undefined routes

## Installation

To use Nano\ORM, simply include the `Router.php` file in your project:

```php
require 'path_to_your_directory/Router.php';
```

## Usage

### Initialize Router

Remember to use the htaccess.

``` apacheconf
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^(.*)$ index.php/$1 [L]
```

You can initialize the router with an optional base path, which is useful if your application is located in a subdirectory.

```php
$router = new Nano\Router\Router('/my_basepath');
```

### Defining Routes

You can define routes for various HTTP methods using the following methods:

#### GET Route

```php
$router->get('/hello', function () {
    echo 'Hello, GET!';
});
```

#### Get the current url
```php
$router->url();
```

### Get urls
```html
<a href="<?php echo $router->url('/users/username'); ?>">Username's profile</a>
```

#### POST Route

```php
$router->post('/submit', function () {
    echo 'Form submitted via POST';
});
```

#### PUT Route

```php
$router->put('/update', function () {
    echo 'Updated via PUT';
});
```

#### DELETE Route

```php
$router->delete('/delete', function () {
    echo 'Deleted via DELETE';
});
```

### Multiple Methods for a Single Route

To handle multiple HTTP methods (e.g., GET and POST) for the same route, use the `all()` method:

```php
$router->all('/multi', function () {
    echo 'This route handles both GET and POST requests';
}, ['GET', 'POST']);
```

### Dynamic Parameters

Routes can include dynamic segments that are captured as parameters:

#### Single Parameter

```php
$router->get('/user/(\d+)', function ($id) {
    echo "User with ID: $id";
});
```

#### Multiple Parameters

```php
$router->get('/product/(\d+)/category/(\w+)', function ($productId, $categoryName) {
    echo "Product ID: $productId in category $categoryName";
});
```

### Redirection

You can easily define routes that perform HTTP redirections:

```php
$router->redirect('/old-page', '/new-page');
```

The redirection can be tested by defining the target route:

```php
$router->get('/new-page', function () {
    echo 'You have been redirected to the new page!';
});
```

### Dispatching Routes

Once routes are defined, you need to dispatch the router to process the current request:

```php
try {
    $router->dispatch(); // Process the current route
} catch (Exception $e) {
    echo $e->getMessage(); // Handle route not found error
}
```

## Example

Here's a complete example:

```php
require 'path_to_your_directory/Router/Router.php';
require 'path_to_your_directory/Template/Engine.php';
require 'path_to_your_directory/Template/Context.php';

$router = new Nano\Router\Router('/my_router');

$template = new Nano\Template\Engine(__DIR__.'/views');
$template->register('url', [$router, 'url']);

$router->get('/', function() use ($template) {
    echo $template->render('home.php');
});

$router->get('/about', function() use ($template) {
    echo $template->render('about.php');
});

// compare url with all registered routes
$router->dispatch();
```

## License and contributing

Contributions are most welcome by forking the Git repository over GitHub and sending a pull request.