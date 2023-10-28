<?php

namespace App\Controller;

use App\Entity\Book;

use App\Form\BookType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    //Affichage table Book avec tri selon username d'author ,fonction recherche selon ref
    #[Route('/showBook', name: 'showBook')]
    public function showBook(BookRepository $bookRepository , Request $req): Response
    {
        //$book=$bookRepository->findAll();
        $book=$bookRepository->triabookauthor();
       
        $form= $this->createForm(SearchType::class);
        $form->handleRequest($req);

        $somme= $bookRepository->sommeScience();
        $nbPublished= $bookRepository->nbPublished();
        $nbUnPublished= $bookRepository->nbUnPublished();

    //Chercher les livres selon username d'author

        
        if($form->isSubmitted()){
            $somme= $bookRepository->sommeScience();
            $nbPublished= $bookRepository->nbPublished();
            $nbUnPublished= $bookRepository->nbUnPublished();
            $Datainput=$form->get('ref')->getData();
            $books=$bookRepository->searchbook($Datainput);
            
            return $this->renderForm('book/showBook.html.twig', [
                'book' => $books,
                'f'=> $form,
                'somme' => $somme,
                'nbPublished' => $nbPublished,
                'nbUnPublished' => $nbUnPublished
            ]);
        }
      
        return $this->renderForm('book/showBook.html.twig', [
            'book' => $book,
            'f' => $form,
            'somme' => $somme,
            'nbPublished' => $nbPublished,
            'nbUnPublished' => $nbUnPublished

        ]);
    }

// Afficher les livres avant année 2023 d'un auteur nb_books=35

    #[Route('/showbookparannee', name: 'showbookparannee')]
    public function showbookparannee(BookRepository $bookRepository, Request $req): Response
    {
        $book=$bookRepository->triabookauthor();
        $form= $this->createForm(SearchType::class);
        $form->handleRequest($req);
        
        $book=$bookRepository->bookparannee();
         $somme= $bookRepository->sommeScience();
         $nbPublished= $bookRepository->nbPublished();
         $nbUnPublished= $bookRepository->nbUnPublished();
         if($form->isSubmitted()){
            $Datainput=$form->get('ref')->getData();
            $books=$bookRepository->searchbook($Datainput);
            $somme= $bookRepository->sommeScience();
            $nbPublished= $bookRepository->nbPublished();
            $nbUnPublished= $bookRepository->nbUnPublished();
            return $this->renderForm('book/showBook.html.twig', [
                'book' => $books,
                'f'=> $form,
                'somme' => $somme,
                'nbPublished' => $nbPublished,
                'nbUnPublished' => $nbUnPublished
            ]);}
        return $this->renderForm('book/showBook.html.twig', [
            'book' => $book,
            'f'=> $form,
            'somme' => $somme,
            'nbPublished' => $nbPublished,
            'nbUnPublished' => $nbUnPublished
        ]);
    }

 //mise à jours la colonne category
    #[Route('/updatebook', name: 'updatebook')]
    public function updatebook(BookRepository $bookRepository, Request $req): Response
    {
        $bookRepository->updatecategory();
        $book=$bookRepository->triabookauthor();
        $form= $this->createForm(SearchType::class);
        $form->handleRequest($req);
        $somme= $bookRepository->sommeScience();
        $nbPublished= $bookRepository->nbPublished();
        $nbUnPublished= $bookRepository->nbUnPublished();
        if($form->isSubmitted()){
            $Datainput=$form->get('ref')->getData();
            $books=$bookRepository->searchbook($Datainput);
            $somme= $bookRepository->sommeScience();
            $nbPublished= $bookRepository->nbPublished();
            $nbUnPublished= $bookRepository->nbUnPublished();
            return $this->renderForm('book/showBook.html.twig', [
                'book' => $books,
                'f'=> $form,
                'somme' => $somme,
                'nbPublished' => $nbPublished,
                'nbUnPublished' => $nbUnPublished
            ]);}

        return $this->renderForm('book/showBook.html.twig', [
            'book' => $book,
            'f'=> $form,
            'somme' => $somme,
            'nbPublished' => $nbPublished,
            'nbUnPublished' => $nbUnPublished
        ]);
    }

//les livres entre 2014 et 2018
    #[Route('/bookIneYear', name: 'bookIneYear')]
    public function bookIneYear(BookRepository $bookRepository, Request $req): Response
    {
            $books=$bookRepository->bookIn();
            $somme= $bookRepository->sommeScience();
            $nbPublished= $bookRepository->nbPublished();
            $nbUnPublished= $bookRepository->nbUnPublished();
            

            return $this->renderForm('book/bookIneYear.html.twig', [
            'books' => $books,
            'somme' => $somme,
            'nbPublished' => $nbPublished,
            'nbUnPublished' => $nbUnPublished
        ]);
    }

// Ajouter un livre
    #[Route('/addBook', name: 'addBook')]
    public function addBook(ManagerRegistry $managerRegistry , Request $req): Response
    {
       $em=$managerRegistry->getManager();
       $book = new Book();
       $form=$this->createForm(BookType::class,$book);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid())
       {
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('showBook');

       }

        return $this->renderForm('book/addBook.html.twig', [
            'f' => $form,
        ]);
    }

//Modifier un livre
    #[Route('/editBook/{ref}', name: 'editBook')]
    public function editBook($ref , BookRepository $bookRepository ,ManagerRegistry $managerRegistry , Request $req): Response
    {
       $em=$managerRegistry->getManager();
       $dataid = $bookRepository->find($ref);
       $form=$this->createForm(BookType::class,$dataid);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid())
       {
        $em->persist($dataid);
        $em->flush();
        return $this->redirectToRoute('showBook');

       }

        return $this->renderForm('book/editBook.html.twig', [
            'f' => $form,
        ]);
    }

//Supprimer un livre
    #[Route('/deleteBook/{ref}', name: 'deleteBook')]
    public function deleteBook($ref , BookRepository $bookRepository ,ManagerRegistry $managerRegistry , Request $req): Response
    {
       $em=$managerRegistry->getManager();
       $dataid = $bookRepository->find($ref);
       $em->remove($dataid);
       $em->flush();
        return $this->redirectToRoute('showBook');

    }

}

