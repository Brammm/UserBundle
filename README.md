Brammm\UserBundle
=================

[![Build Status](https://travis-ci.org/Brammm/UserBundle.png?branch=master)](https://travis-ci.org/Brammm/UserBundle)

A light-weight UserBundle that separates the persisted user entity from the user entity. 

## Install

Via Composer (the bundles aren't added to Packagist just yet, so you have to add the repositories manually):

```json
{
    "repositories": [
        {
            "url": "https://github.com/Brammm/CommonBundle.git",
            "type": "git"
        },
        {
            "url": "https://github.com/Brammm/UserBundle.git",
            "type": "git"
        }
    ],
    "require": {
        "league/color-extractor": "~0.1"
    }
}
```

*This bundle has a hard dependency on my brammm\common-bundle. In the future, I plan on making this optional.*

Enable the Bundles in your `AppKernel.php`

```php
$bundles = [
    // ...
    new Brammm\CommonBundle\BrammmCommonBundle(),
    new Brammm\UserBundle\BrammmUserBundle(),
];
```

### Configuration

This bundle requires some configuration. Firstly, configure your user repository service (*For more information about the user repository service, see Usage*).
```json 
#config.yml
brammm_user:
    user_repository: <user_repository_service>
```

Next, declare the `brammm_user.userprovider` service as your provider, configure an encoder for the `Brammm\User\Model\User` class and enable the provider for one of your firewalls.

```yaml
#security.yml
security:
    providers:
        brammm_userbundle:
            id: brammm_user.userprovider

    encoders:
        Brammm\UserBundle\Model\User:
            algorithm: bcrypt
            cost: 14
            
    firewalls:
        main:
            provider: brammm_userbundle
```

If you want to use the provider `LoginType` form, you also have to configure the necessary parameters for your firewall.

```yaml
#security.yml
firewalls:
    main:
        form_login:
            username_parameter: login[username]
            password_parameter: login[password]
            csrf_parameter: login[_token]
            csrf_provider: form.csrf_provider
        remember_me:
            remember_me_parameter:  login[rememberMe]
```

## Usage

### Entity and Repository

The goal of this user bundle is to split the responsibility of the security user off of the persisted user and create a cleaner codebase.

You will need your own `User` that implements this bundle's `SimpleUserInterface`. 

```php
namespace Acme\DemoBundle\Entity;

use Brammm\UserBundle\Model\SimpleUserInterface

class User implements SimpleUserInterface
{
    public function getUsername() {}

    public function setPassword($password) {}

    public function getPassword() {}

    public function getPlainPassword() {}
} 
```

You'll also need a `UserRepository` that implements `UserRepositoryInterface`.

```php
namespace Acme\DemoBundle\Repository;

use Brammm\UserBundle\Security\UserRepositoryInterface;

interface UserRepositoryInterface
{
    /**
     * Looks for a SimpleUserInterface user
     * Must return one or null
     *
     * @param string $username
     *
     * @return \Brammm\UserBundle\Model\SimpleUserInterface|null
     */
    public function findOneByUsername($username) {}
}
```

You will need to declare this repository as a service to configure it. Simply create the service by using the EntityManager as the factory.

```xml
<service id="acme_demo.repository.user"
         class="Acme\DemoBundle\Repository\UserRepository"
         factory-service="doctrine.orm.entity_manager"
         factory-method="getRepository">
    <argument>AcmeDemoBundle:User</argument>
</service>
```

### Password encoding

The bundle by default registers a Doctrine Event Subscriber that will check any entity implementing `SimpleUserInterface`. If `getPlainPassword()` returns a non-null value, the Event Subscriber will encode the plainPassword using the configured encoder.

If you wish to manually encode passwords for whatever reason, you can use the `brammm_user.encoder` service. The service is a simple adapter for the configured encoder. 

```php
$password = $this->get('brammm_user.encoder')->encodePassword($plainPassword, $salt);
```

### Login Form

By default, the Security component is completely decoupled from the Form component, that's a shame. This bundle tries to rectify that. 

```php
$form = $this->createForm(
    'login', 
    new \Brammm\UserBundle\Form\Model\Login($this->session->get(SecurityContext::LAST_USERNAME))
);
```

You can now render the Login form like any other form in your views. 

**Note:** If you wish to use the `LoginType` form, you must configure the parameters in `security.yml`. See Configuration.

The `LoginFormCreatedListener` will automatically set any Exceptions thrown during log on as form errors. 

## Contributing

Go right ahead, submit a PR. I'm open to suggestions.

## License

The MIT License (MIT). Please see [License File](https://github.com/brammm/user-bundle/blob/master/Resources/meta/LICENSE) for more information.