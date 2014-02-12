<?php

namespace Brammm\UserBundle\Tests\Form\Model;

use Brammm\UserBundle\Form\Model\Login;

class LoginTest extends \PHPUnit_Framework_TestCase
{
    const USERNAME = 'foo';

    public function testCanSetUsernameViaConstruct()
    {
        $model = new Login(self::USERNAME);

        $this->assertEquals(self::USERNAME, $model->getUsername());
    }
} 