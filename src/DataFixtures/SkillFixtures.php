<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        foreach (Skill::SKILLS as $skillName) {
            $skill = new skill();

            $skill->setName($skillName);
            
            $manager->persist($skillName);
            
            $this->addReference('category_' . $this->$skillName, $skill);
        
        }

        $manager->flush();
    }
}
