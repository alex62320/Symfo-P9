<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DbTestController extends AbstractController
{
    #[Route('/db/test/fixtures', name: 'app_db_test_fixtures')]
    public function fixtures(ManagerRegistry $doctrine): Response
    {
        // récupération du repository des catégories
        $repository = $doctrine->getRepository(Category::class);
        // récupération de la liste complète de toutes les catégories
        $categories = $repository->findAll();
        // inspection de la liste
        dump($categories);

        // récupération du repository des tags
        $repository = $doctrine->getRepository(Tag::class);
        // récupération de la liste complète de tous les tags
        $tags = $repository->findAll();
        // inspection de la liste
        dump($tags);

        // récupération du repository des articles
        $repository = $doctrine->getRepository(Article::class);
        // récupération de la liste complète de tous les articles
        $articles = $repository->findAll();

        foreach ($articles as $article) {
            // inspection de l'article
            dump($article);

            // récupération des tags de l'article
            $tags = $article->getTags();

            foreach ($tags as $tag) {
                // inspection du tag
                dump($tag);
            }
        }
        // récupération du repository des pages
        $repository = $doctrine->getRepository(Page::class);
        // récupération de la liste complète de tous les articles
        $pages = $repository->findAll();
        dump($pages);
        
        exit();
    }
    #[Route('/db/test/orm', name: 'app_dbtest_orm')]
    public function orm(ManagerRegistry $doctrine): Response
    {
        // affichez tous les tags
        $repository = $doctrine->getRepository(Tag::class);
        $tags = $repository->findAll();
        dump($tags);

        // récupération de plusieur objets  à partir de son name 
        $tag = $repository->findOneBy(['name' => 'carné']);
        dump($tag);

        // récupération de plusieur objets  à partir de son name 
        $tags = $repository->findBy(['name' => 'carné']);
        dump($tag);

        // recuperation de l'entity manager
        $manager = $doctrine->getManager();

        //suppression d'un objet dans la bdd
        if ($tag) {
            $manager->remove($tag);
            $manager->flush();
        }

        // afficher un seul tag via son id
        $id = 7;
        $tag = $repository->find($id);
        dump($tag->getName());

        $tag->setName('foo bar baz');
        dump($tag->getName());

        // enregistrement de la modification dans la bdd
        $manager->flush();

        $tag = new Tag();
        $tag->setName('le dernier tag');

        dump($tag);

        $manager->persist($tag);
        $manager->flush();
        dump($tag);

        exit();
    }
}