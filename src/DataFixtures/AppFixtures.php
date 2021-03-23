<?php

namespace App\DataFixtures;

use App\Entity\Projets;
use App\Entity\Competences;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        // $projet = new Projets();
        // $projet->setTitre('Réalisation d\'un site vitrine TEST INGENIERIE');
        // $projet->setDescription('Réalisation d\'un site vitrine TEST INGENIERIE');
        // $projet->setImage('projet1-1.png');

        // $manager->persist($projet);

        // $manager->flush();

        $competence = new Competences();
        $competence->setTitre('Magento');
        $competence->setImage('cp11-11.png');
        $competence->setCategorie('cms');

        $manager->persist($competence);

        $manager->flush();
    }
}
