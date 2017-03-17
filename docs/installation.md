# Installation
1) In order to install Lumineer, just add the following to your composer.json. Then run `composer update`:

```bash
"Peaches/Lumineer": "1.0.*"
```

or you can run the `composer require` command from your terminal:

```bash
composer require "Peaches/Lumineer:1.0.*"
```

2) Then in your `bootstrap/app.php` add the service provider:

```php
$app->register(Peaches\Lumineer\LumineerServiceProvider::class);
```

3) In the same `bootstrap/app.php`, if you've chosen to use Facades by uncommenting `$app->withFacades()`, you can simply add an alias like so:

```php
class_alias(Peaches\Lumineer\LumineerFacade::class, 'Lumineer');
```

4) If you are going to use [Middleware](middleware.md) (requires Lumen 5.1 or later) you also need to add the following to `$app->routeMiddleware` array in `bootstrap/app.php`.

```php
$app->routeMiddleware([
	'role'       => \Peaches\Lumineer\Middleware\LumineerRole::class,
	'permission' => \Peaches\Lumineer\Middleware\LumineerPermission::class,
	'ability'    => \Peaches\Lumineer\Middleware\LumineerAbility::class,
]);
```

