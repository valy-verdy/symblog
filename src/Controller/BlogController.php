<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_index')]
    public function index(ArticleRepository $artRepo): Response
    {
        // Récuperer les article
        $articles = $artRepo->findAll();

        // Appel de la vue qui affiche les articles
        // 'articles' = variable côté Twig (vue)
        // $articles = variable côté controller
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }


  
    #[Route('/blog/new', name:'blog_new_art')]
    #[Route('/blog/edit{id}',name: 'blog_edit_art')]
    public function addArticle(Article $article=null , Request $req, EntityManagerInterface $em): Response {

        if(!$article){
            // Si appel de la route new_art 
            $article = new Article();
        }
        // Creation  form

        $form = $this->createForm(ArticleType::class, $article);

        //Traitemnt retour du formulaire
        $form->handleRequest($req);

        if($form->isSubmitted()&& $form->isValid()){
            // dd($article);
            //$article contient les données du form

            // Mettre la date uniquement si nouvel Article 
        if(!$article->getCreatedAt()){
                $article->setCreatedAt(new \DateTimeImmutable());
        }
                $em->persist($article);
                $em->flush();


            //Retour sur la page de tous les article 

            return $this->redirectToRoute('blog_index');

        }
        //Generation de vue
        return $this->render('/blog/edit_art.html.twig', [
            'artForm' => $form->createView(),
            'mode' => $article->getId() != null,
        ]);
    }



    #[Route('/blog/{id}', name: 'blog_detail_art')]
    public function showArticle(Article $article,Request $req,EntityManagerInterface $em): Response {

        $comment = new Comment();
        $form= $this->createForm(CommentType::class, $comment);
        $form->handleRequest($req);


        if($form->isSubmitted() && $form->isValid()){
            $comment->setArticle($article)
            ->setCreatedAt(new \DateTimeImmutable());

            $em->persist($comment);
            $em->flush();
        }

        return $this->render('blog/detail_art.html.twig', [
            'article' => $article,
            'formCom'=>$form->createView(),
        ]);
    }
}
