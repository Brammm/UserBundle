<?php

namespace Brammm\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class BrammmUserExtension extends Extension implements PrependExtensionInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('security')[0];

        $prepend['firewalls'] = $config['firewalls']; // we have to copy over everything from firewalls or we'll get exceptions

        if (isset($config['firewalls']['main'])) {
            $prepend['firewalls']['main']['form_login']['username_parameter']     = 'login[username]';
            $prepend['firewalls']['main']['form_login']['password_parameter']     = 'login[password]';
            $prepend['firewalls']['main']['form_login']['csrf_parameter']         = 'login[_token]';
            $prepend['firewalls']['main']['form_login']['csrf_provider']          = 'form.csrf_provider';
            $prepend['firewalls']['main']['remember_me']['remember_me_parameter'] = 'login[rememberMe]';

            $container->prependExtensionConfig('security', $prepend);
        }
    }
}