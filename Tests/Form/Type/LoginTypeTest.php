<?php

namespace Brammm\UserBundle\Tests\Form\Type;

use Brammm\UserBundle\Form\Model\Login;
use Brammm\UserBundle\Form\Type\LoginType;
use Symfony\Component\Form\Test\TypeTestCase;

class LoginTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'username'   => 'foo',
            'password'   => 'bar',
            'rememberMe' => true
        ];

        $form = $this->factory->create(new LoginType());

        $object = new Login();
        $object
            ->setUsername($formData['username'])
            ->setPassword($formData['password'])
            ->setRememberMe($formData['rememberMe']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
} 