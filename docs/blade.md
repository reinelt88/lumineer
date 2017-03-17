# Blade templates

Three directives are available for use within your Blade templates. What you give as the directive arguments will be directly passed to the corresponding `Lumineer` function.

```php
@role('admin')
    <p>This is visible to users with the admin role. Gets translated to 
    \Lumineer::role('admin')</p>
@endrole

@permission('manage-admins')
    <p>This is visible to users with the given permissions. Gets translated to 
    \Lumineer::can('manage-admins'). The @can directive is already taken by core 
    laravel authorization package, hence the @permission directive instead.</p>
@endpermission

@ability('admin,owner', 'create-post,edit-user')
    <p>This is visible to users with the given abilities. Gets translated to 
    \Lumineer::ability('admin,owner', 'create-post,edit-user')</p>
@endability
```
