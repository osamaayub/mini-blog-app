<?php
namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create the first actor
        $actor = new Actor();
        $actor->setName('Jacqueline McKenzie');
        
        // Add a reference for the first actor for later use
        $this->addReference('actor-1', $actor);
        $manager->persist($actor); // Persist the first actor

        // Create the second actor
        $actor2 = new Actor();
        $actor2->setName('Alexander Ward Demon Guy');
        $this->addReference('actor-2',$actor2);
        $manager->persist($actor2); // Persist the second actor
        //create third actor
        $actor3=new Actor();
        $actor3->setName('John Abraham');
        $this->addReference('actor-3',$actor3);
        $manager->persist($actor3);//persist the third actor
        //actor 4
        $actor4=new Actor();
        $actor4->setName('John Wick');
        $this->addReference('actor-4',$actor4);
        $manager->persist($actor4);//persist the third actor
        // Save the actors to the database
        $manager->flush();
    }
}
?>
