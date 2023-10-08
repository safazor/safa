<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'showAuthor')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', 
            ['n'=>$name]);
    }

    #[Route('/showlist', name: 'showlist')]
    public function list(){
        $authors = array(
            array('id' => 1, 'picture' => '/images/victor-hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
            );
            return $this->render('author/list.html.twig', ['authors' => $authors]);
}

#[Route('/auhtorDetails/{id}', name: 'd')]
    public function auhtorDetails($id)
    {
        return $this->render('author/showAuthor.html.twig', 
            ['i'=>$id]);
  
    }



    #[Route('/listAuthor', name: 'list_author')]
public function listAuthor(AuthorRepository $authoreprosiroty): Response
{
    $list=$authoreprosiroty->findAll();
    return $this->render('author/listAuthor.html.twig', [
        'list' => $list
    ]);

}

#[Route('/AddStatique', name: 'Add_Statique')]
public function addstatiqu(AuthorRepository $repository): Response
{
    $author1 = new Author();
    $author1->setUsername("test");
    $author1->setEmail("test@gmail.com");

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($author1);
    $entityManager->flush();

    $list = $repository->findAll();

    return $this->render('author/listAuthor.html.twig', [
        'list' => $list
    ]);
}


    #[Route('/Add', name: 'app_Add')]
    public function Add(Request $request)
    {
        $author=new Author();
        $form =$this->createForm(AuthorType::class, $author);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('Add_Statique');
        }
    
        return $this->render('author/Add.html.twig',['f'=>$form->createView()]);
    }
    
    #[Route('/edit/{id}', name: 'app_edit')]
public function edit(AuthorRepository $repository, $id, Request $request)
{
    $author = $repository->find($id);

    if (!$author) {
        throw $this->createNotFoundException('Aucun auteur trouvé pour l\'id ' . $id);
    }

    $form = $this->createForm(AuthorType::class, $author);
    $form->add('Edit', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush(); 
        return $this->redirectToRoute("Add_Statique");
    }

    return $this->render('author/edit.html.twig', [
        'f' => $form->createView(),
    ]);
}

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id, AuthorRepository $repository)
    {
        $author = $repository->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('Add_Statique');
    }
    #[Route('/AddStatistique', name: 'app_AddStatistique')]

    public function addStatistique(EntityManagerInterface $entityManager): Response
    {
        
        $author1 = new Author();
        $author1->setUsername("test"); 
        $author1->setEmail("test@gmail.com"); 

        $entityManager->persist($author1);
        $entityManager->flush();

        return $this->redirectToRoute('Add_Statique'); 
    }
}

