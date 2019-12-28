<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/inscription", name="security-registration")
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @param $encoder
	 * @return Response
	 */
    public function registration(Request $request, EntityManagerInterface $entityManager,
                                 UserPasswordEncoderInterface $encoder) {
    	
    	$user = new User();
        $formUser = $this->createForm(RegistrationType::class, $user);
        
        $formUser->handleRequest($request);
        
        if($formUser->isSubmitted() && $formUser->isValid()) {
        	
        	// Hashage du mot de passe
        	$hash = $encoder->encodePassword($user, $user->getPassword());
        	
        	$user ->setPassword($hash);
        	
        	$entityManager->persist($user);
        	$entityManager->flush();
        	
        	return $this->redirectToRoute('connection');
        }
        
        return $this->render('security/registration.html.twig', [
        	'formUser' => $formUser->createView()
        ]);
    }
    
    /**
     *@Route("/login", name="login")
     */
    public function login() {
    	
    	return $this->render('security/login.html.twig');
    }
    
    /**
     *@Route("/logout", name="logout")
     */
    
    public function logout(){}
}
