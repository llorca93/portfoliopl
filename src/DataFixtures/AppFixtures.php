<?php

namespace App\DataFixtures;

use App\Entity\Apropos;
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

        // $competence = new Competences();
        // $competence->setTitre('JQuery');
        // $competence->setImage('cp10-10.png');
        // $competence->setCategorie('frameworks');

        // $manager->persist($competence);

        // $manager->flush();

        $apropos = new Apropos();
        $apropos->setApropos('Dynamique, autonome, aimant le travail d’équipe, passionné par la culture et le digital, je mets, aujourd’hui mes compétences et mes expériences au service d’entreprises qui souhaitent faire de leur visibilité sur le web, un atout majeur.');

        $manager->persist($apropos);
        $manager->flush();
    }
}
