# Role
Create a Role model inside `app/models/Role.php` using the following example:

```php
<?php namespace App;

use Peaches\Lumineer\LumineerRole;

class Role extends LumineerRole
{
}
```

The `Role` model has three main attributes:
- `name` &mdash; Unique name for the Role, used for looking up role information in the application layer. For example: "admin", "owner", "employee".
- `display_name` &mdash; Human readable name for the Role. Not necessarily unique and optional. For example: "User Administrator", "Project Owner", "Widget  Co. Employee".
- `description` &mdash; A more detailed explanation of what the Role does. Also optional.

Both `display_name` and `description` are optional; their fields are nullable in the database.

