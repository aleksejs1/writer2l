<?php

namespace App\DataFixtures;

use App\Entity\Chapter;
use App\Entity\Character;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Project;
use App\Entity\Scene;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{
    private $encoderFactory;
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function load(ObjectManager $manager)
    {
        /** User 1 */
        $user = $this->createUser('bob', 'qwerty');
        $manager->persist($user);

        $project = $this->createProject($user, 'Book1', 'Bobby');
        $manager->persist($project);

        $chapter = $this->createChapter($project, 'Chapter 1', 'About nothing');
        $manager->persist($chapter);

        $scene = $this->createScene($chapter, 'Scene 1', 'Once upon a time...');
        $manager->persist($scene);

        $character = $this->createCharacter($project, 'John');
        $manager->persist($character);

        $location = $this->createLocation($project, 'Home');
        $manager->persist($location);

        $item = $this->createItem($project, 'Gun');
        $manager->persist($item);


        /** User 2 */
        $user2 = $this->createUser('garry', 'qwerty');
        $manager->persist($user2);

        $project2 = $this->createProject($user2, 'Book2', 'Garry');
        $manager->persist($project2);

        $chapter2 = $this->createChapter($project2, 'Chapter one', 'About something');
        $manager->persist($chapter2);

        $scene2 = $this->createScene($chapter2, 'Scene one', 'Twice upon a time...');
        $manager->persist($scene2);

        $character2 = $this->createCharacter($project2, 'Stan');
        $manager->persist($character2);

        $location2 = $this->createLocation($project2, 'Office');
        $manager->persist($location2);

        $item2 = $this->createItem($project2, 'Flower');
        $manager->persist($item2);

        $manager->flush();
    }

    private function createUser(string $name, string $password)
    {
        $user = new User();
        $user
            ->setUsername($name)
            ->setPassword($this->encoderFactory->getEncoder(User::class)->encodePassword($password, null))
        ;

        return $user;
    }

    private function createProject(User $user, string $title, string $author)
    {
        $project = new Project();
        $project
            ->setTitle($title)
            ->setAuthorsName($author)
            ->setUser($user)
        ;

        $user->addProject($project);

        return $project;
    }

    public function createChapter(Project $project, string $title, ?string $description)
    {
        $chapter = new Chapter();
        $chapter
            ->setProject($project)
            ->setTitle($title)
            ->setDescription($description)
        ;

        $project->addChapter($chapter);
        $chapter->setPosition($project->getChapters()->count());

        return $chapter;
    }

    public function createScene(Chapter $chapter, ?string $title, ?string $content)
    {
        $scene = new Scene();
        $scene
            ->setChapter($chapter)
            ->setTitle($title)
            ->setContent($content)
            ->setStatus(Scene::STATUS_OUTLINE)
            ->setGoalType(Scene::GOAL_ACTION)
        ;

        $chapter->addScene($scene);
        $scene->setPosition($chapter->getScenes()->count());

        return $scene;
    }

    public function createCharacter(Project $project, ?string $shortName)
    {
        $character = new Character();
        $character
            ->setProject($project)
            ->setShortName($shortName)
        ;

        return $character;
    }

    public function createLocation(Project $project, ?string $title)
    {
        $location = new Location();
        $location
            ->setProject($project)
            ->setTitle($title)
        ;

        return $location;
    }

    public function createItem(Project $project, ?string $title)
    {
        $item = new Item();
        $item
            ->setProject($project)
            ->setTitle($title)
        ;

        return $item;
    }
}
