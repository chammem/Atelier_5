<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\MinMaxType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;



class AuthorController extends AbstractController
{
   
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
  

    #[Route('/showAuthor', name: 'showAuthor')]
    public function showAuthor(AuthorRepository $authorRepository , Request $req): Response
    {    
         $author = $authorRepository->triaauthor();
         $form= $this->createForm(MinMaxType::class);
         $form->handleRequest($req);

         if($form->isSubmitted()){
           $min = $form->get('min')->getData();
           $max = $form->get('max')->getData();
            $authors=$authorRepository->searchNbBook($min , $max);

            return $this->renderForm('author/showAuthor.html.twig', [
                'author' => $authors,
                'f'=> $form
            ]);}
        return $this->renderForm('author/showAuthor.html.twig', [
            'author'=> $author,
            'f'=> $form
        ]);
    }

    #[Route('/showdelete', name: 'showdelete')]
    public function showdelete(AuthorRepository $authorRepository , Request $req): Response
    {    
        $authorRepository->deleteZero();
         $form= $this->createForm(MinMaxType::class);
         $form->handleRequest($req);
         $author = $authorRepository->findAll();
         
         $author = $authorRepository->triaauthor();
         if($form->isSubmitted()){
            $min = $form->get('min')->getData();
            $max = $form->get('max')->getData();
             $authors=$authorRepository->searchNbBook($min , $max);
 
             return $this->renderForm('author/showAuthor.html.twig', [
                 'author' => $authors,
                 'f'=> $form
             ]);}
        
        return $this->renderForm('author/showAuthor.html.twig', [
            'author'=> $author,
            'f'=> $form
        ]);
    }
    

    #[Route('/addAuthor', name: 'addAuthor')]
    public function addAuthor(ManagerRegistry $managerRegistry, Request $req): Response
    {    
        $em=$managerRegistry->getManager();
        $author=new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($author);
            $em->flush();
            return $this->redirect('showAuthor');
        }
        return $this->renderForm('author/addAuthor.html.twig', [
            'f'=>$form
        ]);

    }

    #[Route('/editAuthor/{id}', name: 'editAuthor')]
    public function editAuthor($id ,AuthorRepository $authorRepository, ManagerRegistry $managerRegistry, Request $req): Response
    {    
        
        $em=$managerRegistry->getManager();
        $dataid=$authorRepository->find($id);
        $form=$this->createForm(AuthorType::class,$dataid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showAuthor');
        }

        return $this->renderForm('author/editAuthor.html.twig', [
            'f'=>$form
           
        ]);

       
    }

    #[Route('/deleteAuthor/{id}', name: 'deleteAuthor')]
    public function deleteAuthor($id ,AuthorRepository $authorRepository, ManagerRegistry $managerRegistry): Response
       {    
        
        $em=$managerRegistry->getManager();
        $dataid=$authorRepository->find($id);
        $em->remove($dataid);
        $em->flush();
       
            return $this->redirectToRoute('showAuthor');
           
               
            
        }
        
    }