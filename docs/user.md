# User

Next, use the `LumineerUserTrait` trait in your existing `User` model. For example:

```php
<?php

use Peaches\Lumineer\Traits\LumineerUserTrait;

class User extends Eloquent
{
    use LumineerUserTrait; // add this trait to your user model

    ...
}
```

This will enable the relation with `Role` and add the following methods `roles()`, `hasRole($name)`, `can($permission)`, and `ability($roles, $permissions, $options)` within your `User` model.

Don't forget to dump composer autoload

```bash
composer dump-autoload
```

**At this point you are ready to go.**