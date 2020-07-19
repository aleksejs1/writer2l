<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectListControllerTest extends WebTestCase
{
    public function testProjectListAnon()
    {
        $client = static::createClient();
        $client->request('GET', 'en/project/');
        self::assertResponseStatusCodeSame(302);
    }
}
