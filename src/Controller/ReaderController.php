<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\AuthorRepository;
use App\Repository\ReaderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReaderController extends AbstractController
{
    #[Route('/reader', name: 'app_reader')]
    public function index(): Response
    {
        return $this->render('reader/index.html.twig', [
            'controller_name' => 'ReaderController',
        ]);
    }

    #[Route('/showReader', name: 'showReader')]
    public function showReader(ReaderRepository $readerRepository ): Response
    {    $reader = $readerRepository->findAll();
        //$author = $authorRepository->searchalphabet();
        return $this->render('reader/showReader.html.twig', [
            'reader'=> $reader
        ]);
    }

    #[Route('/addReader', name: 'addReader')]
    public function addReader(ManagerRegistry $managerRegistry , Request $req): Response
    {
       $em=$managerRegistry->getManager();
       $reader = new Reader();
       $form=$this->createForm(ReaderType::class,$reader);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid())
       {
        $em->persist($reader);
        $em->flush();
        return $this->redirectToRoute('showReader');

       }

        return $this->renderForm('reader/addReader.html.twig', [
            'f' => $form,
        ]);
    }

    #[Route('/editReader/{id}', name: 'editrReader')]
    public function editReader($id , ReaderRepository $readerRepository ,ManagerRegistry $managerRegistry , Request $req): Response
    {
       $em=$managerRegistry->getManager();
       $dataid=$readerRepository->find($id);
       $form=$this->createForm(ReaderType::class,$dataid);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid())
       {
        $em->persist($dataid);
        $em->flush();
        return $this->redirectToRoute('showReader');

       }

        return $this->renderForm('reader/editReader.html.twig', [
            'f' => $form,
        ]);
    }

    #[Route('/deleteReader/{id}', name: 'deleteReader')]
    public function deleteReader($id , ReaderRepository $readerRepository ,ManagerRegistry $managerRegistry , Request $req): Response
    {
       $em=$managerRegistry->getManager();
       $dataid = $readerRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showReader');

      

      
    }
}
