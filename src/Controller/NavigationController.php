<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavigationController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    function home()
    {
        return $this->render('accueil/accueil.html.twig');
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
