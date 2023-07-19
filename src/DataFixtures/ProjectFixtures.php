<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Skill;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (Project::PROJECTS as $key => $value) {

            $project = new Project();
            
            $project->setTitle($value["title"])
            ->setCreatedAt(new DateTimeImmutable($value["createdAt"]))
            ->setDuration($value["duration"])
            ->setDescription($value["description"])
            ->setGithub($value["github"])
            ->setPicture($value["picture"]);
            foreach (Skill::SKILLS as $skill) {
                if (in_array($skill, $value['skills'])) {
                    $project->addSkill($this->getReference('skill_' . $skill));
                }
            }

            $manager->persist($project);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
        SkillFixtures::class,
        ];
    }
}
