<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectListControllerTest extends WebTestCase
{
    public function testProjectListAnon()
    {
        $client = static::createClient();
        $client->request('GET', '/project/list');

        $this->assertResponseRedirects('/login');
    }
}
