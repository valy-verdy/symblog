<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Article;
use App\Entity\Comment;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Pour utiliser Faker
        $faker = \Faker\Factory::create('fr_FR');


        // Génération Catégories
        for($c=0; $c<4; $c++){
            $cat = new Category();
            $cat->setName($faker->word());
            $manager->persist($cat);

            // Entre 1 et 4 articles par categorie
            for ($i=0; $i<mt_rand(1, 4); $i++){


                        
                // Gération d'un article
                $art = new Article();
                $art->setTitle($faker->sentence());
                $art->setContent($faker->paragraph(5, true));
                $art->setCreatedAt(new \DateTimeImmutable());
                $art->setPicture("https://loremflickr.com/g/350/240/paris");
                $art->setCategory($cat);

                // persister cet article
                $manager->persist($art);
                // Entre 1 et 6 commentaire par article
                for ($j=0; $j<mt_rand(1, 6); $j++){
                    $com = new Comment();
                    $com->setAuthor($faker->name())
                    ->setContent($faker->sentence())
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setArticle($art);

                    $manager->persist($com);
                }
            }
        
        // Ecriture dans la BDD
        $manager->flush();
    }
}
}
