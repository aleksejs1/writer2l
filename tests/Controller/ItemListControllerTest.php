<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemListControllerTest extends WebTestCase
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

        $url = '/en/project/' . $project->getId() . '/item/';

        $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('td', 'Gun');
        self::assertSelectorExists('td:contains("Gun")');
        self::assertSelectorNotExists('td:contains("Flower")');
    }
}
