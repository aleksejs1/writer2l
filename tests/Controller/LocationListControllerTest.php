<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationListControllerTest extends WebTestCase
{
    public function testSuccess()
    {
        $client = static::createClient();
        $client->request('GET', 'en/');
        $client->submitForm('Sign in', ['username' => 'bob', 'password' => 'qwerty']);

        $project = $client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Project::class)
            ->findOneBy(['title' => 'Book1'])
        ;

        $url = '/en/project/' . $project->getId() . '/location/';

        $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('td', 'Home');
        self::assertSelectorExists('td:contains("Home")');
        self::assertSelectorNotExists('td:contains("Office")');
    }
}
