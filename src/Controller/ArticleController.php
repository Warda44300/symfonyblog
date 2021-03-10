<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;



class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article")
     */
    public function index(LoggerInterface $logger): Response
    {
        
        $bdd_articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
      
        return $this->render('article/list.html.twig', [
    
            'title' => 'coucou',
            'subtitle' => 'au boulot !',
            'article' => $bdd_articles,
        ]);
    }
    /**
     * @Route("/articles", name="articles")
     */
     public function boutique():Response
    {
        //$categorie = $request->query->get("category");

        $articles = $this->getDoctrine()
        ->getRepository(Article::class)
        ->findAll();
        
        return $this->render('article/articles.html.twig', [
            "articles" => $articles,
        ]);
    }

      /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail($id): Response
    {
        
        $bdd_article = $this->getDoctrine()
            
            ->getRepository(Article::class)
            ->find($id);
        return $this->render('article/detail.html.twig', [
            'article' => $bdd_article,
        ]);
    }
     /** 
    *@Route("/creation", name= "article_create")
    */
public function create(Request $request,LoggerInterface $logger):Response
{

    $form = $this->createFormBuilder(null, array(
        'csrf_protection' => false,
    ))
        ->add('title', TextType::class)
        ->add('image', TextType::class)
        ->add('description',TextType::class)
        ->add('save', SubmitType::class, ['label' => 'Create Article'])
        //->add('submit', 'submit')
        ->getForm();
       
          
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $article = new Article();
            $article->setImage($data["image"]);
            $article->setTitle($data["title"]);
            $article->setDescription($data["description"]);
            $entityManager->persist($article);
            $entityManager->flush();
           
        }

        
        return $this->render('article/creation.html.twig', [
            'form' => $form->createView(),
        
         
        ]);

}
}
