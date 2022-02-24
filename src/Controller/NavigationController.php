<?php

namespace App\Controller;

use App\Entity\Salle;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavigationController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    function home(ManagerRegistry $doctrine)
    {
        $allSalles = $doctrine->getManager()->getRepository(Salle::class)->findAll();

        return $this->render(
            'accueil/accueil.html.twig',
            [
                'salles' => $allSalles
            ]
        );
    }

    /**
     * @Route("/films", name="films")
     */
    function films()
    {
        $films = [
            "Les dents de la mer",
            "spiderman",
            "Batman",
        ];

        return $this->render(
            'films/films.html.twig',
            [
                "films" => $films,
            ]
        );
    }

    /**
     * @Route("/redirect", name="redirect")
     */
    function homeRedirect()
    {
        return $this->redirectToRoute("accueil");
    }
}
