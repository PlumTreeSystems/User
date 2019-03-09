Description
===========
This is a Symfony bundle, that extends the symfony user, without adding too many unnecessary features.

Installation
============

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require plumtreesystems/user-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `/configu/bundles.php` file of your project:

```php
<?php
// bundles.php

// ...
return [
    //...
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    //...
    PlumTreeSystems\UserBundle\PlumTreeSystemsUserBundle::class => ['all' => true],
    //...
];
```
### Step 3: Create and import configurations
Create a configuration for the bundle

```yaml
#config/packages/pts_user_bundle.yaml

pts_user:
    user_class: <classname of the class that extends one of the abstract classes>
```

Import the routes

```yaml
#config/routes.yaml
pts_user:
    resource: "@PlumTreeSystems/UserBundle/Resources/config/routes/securityRoutes.yml"
    prefix:   /
```

Basic Usage
===========

### Step 1: Create a User Entity
Create a user entity, that extends one of the existing abstract (TokenUser|User...) entities.

```php
// App/Entity/User.php
namespace App\Entity;

use PlumTreeSystems\UserBundle\Entity\User as PTSUser;

class User extends PTSUser {
    /...
}
```
### Step 2: Reference the class name in the configuration
Add this user class to the `config/packages/pts_user_bundle.yaml` configuration file.

```yaml
#config/packages/pts_user_bundle.yaml

pts_user:
    user_class: 'App\Entity\User'
```

### Step 3: Create a user instance
If the extended user does not bring any additional fields, the create user command can be used `php bin/console pts:user:create`

*note: when implementing own user, and not overriding password creation flow, use the `$user->setPlainPassword('1234')` method to automatically encode it prior to persisting it to the database*

### Step 4: You can now login
Access the imported login route ex: `mywebsite.com/`*`[prefix]`*`login`

### Customization
To customize the login form add a template file `/templates/bundles/PlumTreeSystemsUserBundle/security/login.html.twig`
Preferably copying and modifying the original

