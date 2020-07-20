<?php

namespace App\Tests\Controller;

use App\Entity\Chapter;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectShowControllerTest extends WebTestCase
{
    public function testSuccess()
    {
        $client = static::createClient();
        $client->request('GET', 'en/');
        $client->submitForm('Sign in', ['username' => 'bob', 'password' => 'qwerty']);

        $em = $client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
        ;
        $project = $em->getRepository(Project::class)->findOneBy(['title' => 'Book1']);
        $chapter = $em->getRepository(Chapter::class)->findOneBy(['title' => 'Chapter 1']);

        $url = '/en/project/' . $project->getId() . '/chapter/' . $chapter->getId();

        $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('p', $chapter->getDescription());
        self::assertSelectorExists('span:contains("Chapter 1")');
        self::assertSelectorNotExists('span:contains("Chapter one")');
    }
}
