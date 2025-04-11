<?php
namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // First Movie - Deep Blue Sea 2
        $movie = new Movie();
        $movie->setTitle('Deep Blue Sea 2');
        $movie->setReleaseYear(2018);
        $movie->setDescription('A shark and action movie');
        $movie->setMovieImg('https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRXibMeMFLu-Wet6JvuX7ZcpsoOxUBnGn-lYStBqdHJDhDsxTNy');
        $movie->addActor($this->getReference('actor-1', Actor::class));  // Fetch the actor reference from ActorFixtures

        $manager->persist($movie);
        //adding data to the pivot table
         //second movie 
         $movie2 = new Movie();
         $movie2->setTitle('Deep Blue Sea');
         $movie2->setReleaseYear(2023);
         $movie2->setDescription('A shark and action movie');
        $movie2->setMovieImg('https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRXibMeMFLu-Wet6JvuX7ZcpsoOxUBnGn-lYStBqdHJDhDsxTNy');
        $movie2->addActor($this->getReference('actor-2', Actor::class));  // Fetch the actor reference from ActorFixtures

        $manager->persist($movie2);
        //adding data to the pivot table
        $movie3 = new Movie();
        $movie3->setTitle('Darkknight');
        $movie3->setReleaseYear(2024);
        $movie3->setDescription('A Action Movie');
        $movie3->setMovieImg('data:image/jpeg;base64,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');
        //adding data to the pivot table
        $movie3->addActor($this->getReference('actor-3', Actor::class));
        $manager->persist($movie3);
        //adding data to the pivot table
        $manager->flush();

    }

    // Get dependencies to ensure ActorFixtures is loaded first
    public function getDependencies(): array
    {
        return [
            ActorFixtures::class,  // ActorFixtures must be loaded first
        ];
    }
}
?>
