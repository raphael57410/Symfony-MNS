<?php

namespace App\Controller;

use App\Entity\Films;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class FormController extends AbstractController
{

    /**
     * @Route("/formulaire", name="formulaire")
     * @Route("updateFilm/{id}", name="update_film")
     * 
     */
    function formulaire(ManagerRegistry $doctrine, Request $request, $id = null)
    {
        !$entityManager = $doctrine->getManager();
        $isEditor = false;

        if (isset($id)) {
            $film = $entityManager->getRepository(Films::class)->find($id);
            $isEditor = true;
            if (!isset($film)) {
                return $this->redirectToRoute("read_film");
            }
        } else {
            // on crée une nouvelle instance de films
            $film = new Films;
        }


        // on envoie a la vu le formulaire
        $form = $this->createFormBuilder($film)
            ->add("title", TextType::class)
            ->add("director", TextType::class)
            ->add("gender", TextType::class)
            ->add("save", SubmitType::class)
            ->getForm();

        // on recupere le formulaire et si il a etait submit et si 
        // il est valide on envoie les donnés en BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $films = $form->getData();
            $entityManager->persist($films);
            $entityManager->flush();

            // redirection vers la listes des films
            return $this->redirectToRoute('read_film');
        }

        return $this->render('formulaire/form.html.twig', [
            'form' => $form->createView(),
            'isEditor' => $isEditor,
        ]);
    }
}
