<?php

namespace App\Controller;

use App\Entity\Films;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmsController extends AbstractController
{
    /**
     * @Route("/createFilm", name="create_film")
     */
    public function createFilm(ManagerRegistry $doctrine): Response
    {
        // on utilise ManagerRegistry pour interargire avec la base de données
        $entityManager = $doctrine->getManager();

        // on crée une nouvelle instance de Films
        $film = new Films;

        // on lui attribu des valeurs
        $film->setTitle('Spiderman');
        $film->setDirector('Jon Watts');
        $film->setGender('Action');

        // on prépare la requete
        $entityManager->persist($film);
        // on envoie la requete en base de données
        $entityManager->flush();

        return new Response('le film a été ajouté avec l\'id' . $film->getId());
    }

    /**
     * @Route("/readFilm", name="read_film")
     */
    public function readFilm(ManagerRegistry $doctrine): Response
    {

        // on récupére tout les films de la base de données
        $allFilms = $doctrine->getManager()->getRepository(Films::class)->findAll();

        // on envoie la vue les données de notre base
        return $this->render(
            'films/films.html.twig',
            [
                "films" => $allFilms,
            ]
        );
    }

    /**
     * @Route("/updateFilm", name="update_film")
     */
    public function updateFilm(ManagerRegistry $doctrine): Response
    {
        // on update un film
    }

    /**
     * @Route("/deleteFilm", name="delete_film")
     */
    public function deleteFilm(ManagerRegistry $doctrine): Response
    {
        // on delete un film
    }
}
