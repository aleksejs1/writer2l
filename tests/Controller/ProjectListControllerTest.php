<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectListControllerTest extends WebTestCase
{
    public function testAnonHasNoAccess()
    {
        $client = static::createClient();
        $client->request('GET', 'en/project/');
        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertSelectorExists('h1:contains("Please sign in")');
    }

    public function testSuccess()
    {
        $client = static::createClient();
        $client->request('GET', 'en/');
        $client->submitForm('Sign in', ['username' => 'bob', 'password' => 'qwerty']);
        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertSelectorExists('h1:contains("Project index")');
        self::assertSelectorExists('a:contains("Book1")');
        self::assertSelectorNotExists('a:contains("Book2")');
    }
}
