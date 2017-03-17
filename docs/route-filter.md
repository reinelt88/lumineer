# Route filter

Lumineer roles/permissions can be used in filters by simply using the `can` and `hasRole` methods from within the Facade:

```php
Route::filter('manage_posts', function()
{
    // check the current user
    if (!Lumineer::can('create-post')) {
        return Redirect::to('admin');
    }
});

// only users with roles that have the 'manage_posts' permission will be able to access any admin/post route
Route::when('admin/post*', 'manage_posts');
```

Using a filter to check for a role:

```php
Route::filter('owner_role', function()
{
    // check the current user
    if (!Lumineer::hasRole('Owner')) {
        App::abort(403);
    }
});

// only owners will have access to routes within admin/advanced
Route::when('admin/advanced*', 'owner_role');
```

As you can see `Lumineer::hasRole()` and `Lumineer::can()` checks if the user is logged in, and then if he or she has the role or permission.
If the user is not logged the return will also be `false`.