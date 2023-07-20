<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 0; $i < 5 ; $i++) {

            $contact = new Contact();
            $contact->setFullName($faker->name())
                ->setEmail($faker->email())
                ->setSubject($faker->sentence())
                ->setMessage($faker->text());

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
