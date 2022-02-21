<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", methods={"GET", "POST"})
     */
    function index()
    {
        $prenom = $_GET['prenom'];
        dd("salut $prenom ");
    }

    /**
     * @Route("/test", name="test")
     */
    function test(Request $requetForm)
    {
        // $req = Request::createFromGlobals();

        $prenom = $requetForm->query->get('prenom', 0);
        dd("salut $prenom ");
    }

    /**
     * @Route("/profile/{prenom?raph}", name="profile")
     */
    function profile($prenom)
    {
        dd("salut $prenom ");
    }

    /**
     * @Route("/ville/{ville}", name="ville")
     */
    function ville($ville)
    {
        //dd("Vous habitez à $ville ");
        return $this->render('default/index.html.twig', ['ville' => $ville]);
    }

    /**
     * @Route("/salle/{salle?2}/seance/{seance?1}", name="salle")
     */
    function salle($salle, $seance)
    {
        //dd("Vous habitez à $salle ");
        return $this->render(
            'salle/index.html.twig',
            [
                'salle' => $salle,
                'seance' => $seance
            ]
        );
    }
}
