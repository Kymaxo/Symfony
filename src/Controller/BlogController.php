<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController {
	/**
	 * @Route("/blog", name="blog")
	 * @param ArticleRepository $repo
	 * @return Response
	 */
    public function index(ArticleRepository $repo) {
    	
        $articles = $repo->findAll();
    	
        return $this->render('blog/index.html.twig', [
	        	'controller_name' => "BlogController",
		        'articles' =>$articles
	        ]);
    }
    
    /**
     *@Route("/", name="home")
     */
    public function home() {
    	
    	return $this->render('blog/home.html.twig', [
		    
    		'title' => 'Bienvenue dans ce blog !'
		    ]);
    }
	
	/**
	 * @Route("/blog/new", name="create-article")
	 * @Route("/blog/{id}/edit", name="edit-article")
	 * @param Article $article
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 * @throws \Exception
	 */
    public function createOrEditArticle(Article $article = null, Request $request, EntityManagerInterface $entityManager) {
    	
    	if(! $article) {
		    $article = new Article();
	    }
    	
    	$form = $this->createForm(ArticleType::class, $article);
    	
    	$form->handleRequest($request);
    	
    	if($form->isSubmitted() && $form->isValid()) {
		    if(! $article->getId()) {
			    $article->setCreatedAt(new \DateTime());
		    }
		    $entityManager->persist($article);
    		$entityManager->flush();
    		
    		return $this->redirectToRoute('show-article', [
    			'id' => $article-> getId()
		    ]);
	    }
    	
	    return $this->render('blog/create.html.twig', [
	    	'formArticle' =>$form->createView(),
			'editMode' => $article->getId() !== null
	    ]);
    }
	
	/**
	 * @Route("/blog/{id}", name="show-article")
	 * @param Article $article
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
    public function showArticle(Article $article, Request $request, EntityManagerInterface $entityManager) {
    	
    	$comment = new Comment();
    	
    	$formComment = $this->createForm(CommentType::class, $comment);
    	
    	$formComment->handleRequest($request);
    	
    	if($formComment->isSubmitted() && $formComment->isValid()) {
    		
    		$comment->setCreatedAt(new \DateTime())
			        ->setArticle($article);
    		
    		$entityManager->persist($comment);
    		$entityManager->flush();
    		
    		return $this->redirectToRoute('show-article', ['id' => $article->getId()]);
	    }
    	
    	return $this->render('blog/show.html.twig', [
    		'article' => $article,
		    'commentForm' =>$formComment->createView()
	    ]);
    }
}
