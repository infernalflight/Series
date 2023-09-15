<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SerieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->addSeries($manager);
        $manager->flush();
    }

    private function addSeries(ObjectManager &$manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i<50; $i++) {
            dump($faker->words(20, true));
            $serie = new Serie();
            $serie
                ->setName($faker->firstName('M') . ' ' . $faker->lastName())
                ->setGenres($faker->randomElement(['SF', 'comedy', 'thriller', 'western']))
                ->setFirstAirDate($faker->dateTimeBetween(new \DateTime("-2 year"), new \DateTime()))
                ->setPopularity($faker->numberBetween(0, 1000))
                ->setVote($faker->numberBetween(0, 10))
                ->setStatus($faker->randomElement(['Canceled', 'ended', 'returning']))
                ->setBackdrop('backdrops.jpg')
                ->setPoster('poster.jpg')
                ->setDateCreated()
            ;

            $manager->persist($serie);
        }
    }
}
