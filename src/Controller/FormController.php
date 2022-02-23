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

            // on recupere le film avec son id
            $film = $entityManager->getRepository(Films::class)->find($id);
            $isEditor = true;

            if (!isset($film)) {
                return $this->redirectToRoute("read_film");
            }

            //### condition quand on change les valeur d'un film ###
            if (!empty($_POST)) {

                //on recupere les données rentré dans les inputs
                $newTitle = $_POST['form']['title'];
                $newDirector = $_POST['form']['director'];
                $newGender = $_POST['form']['gender'];

                // on change les valeurs avec les setters
                $film->setTitle($newTitle);
                $film->setDirector($newDirector);
                $film->setGender($newGender);

                // on prepare et ajoute en BDD les changements
                $entityManager->persist($film);
                $entityManager->flush();
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
            ->add(
                "save",
                SubmitType::class,
                ['attr' => ['onclick' => 'return confirm("valider ?")']]
            )
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
