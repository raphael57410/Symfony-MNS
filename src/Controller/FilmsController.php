<?php

namespace App\Controller;

use App\Entity\Films;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

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
     * @Route("/sortFilms", name="sort_films")
     */
    public function readFilm(ManagerRegistry $doctrine): Response
    {
        $genre = [];
        $sortFilms = [];
        $filmNumber = 0;



        // on récupére tout les films de la base de données
        $allFilms = $doctrine->getManager()->getRepository(Films::class)->findAll();

        // Condition pour trier les films par genre
        if (!empty($_POST)) {
            foreach ($allFilms as $film) {
                if ($film->getGender() == $_POST['gender']) {
                    array_push($sortFilms, $film);
                }
            }

            // Si le tableau n'est pas vide on change valeur de 
            // tous les films avec la nouvelle valeur des films trié
            if (!empty($sortFilms)) {
                $allFilms = $sortFilms;
            }
        }

        foreach ($allFilms as $film) {
            if (!in_array($film->getGender(), $genre)) {
                array_push($genre, $film->getGender());
            }
        }

        $filmNumber = count($allFilms);


        // on envoie la vue les données de notre base
        return $this->render(
            'films/films.html.twig',
            [
                "films" => $allFilms,
                "genres" => $genre,
                "filmNumber" => $filmNumber,
            ]
        );
    }

    /**
     * @Route("/deleteFilm/{id}", name="delete_film")
     */
    public function deleteFilm(Films $film = null, ManagerRegistry $doctrine, $id): Response
    {

        $entityManager = $doctrine->getManager();
        $film = $entityManager->getRepository(Films::class)->find($id);

        // on vérifie si un film existe
        if ($film == null) {
            throw $this->createNotFoundException('film non trouvé.');
        }

        // on le supprime et on flush pour la BDD
        $entityManager->remove($film);
        $entityManager->flush();

        return $this->redirectToRoute('read_film');
    }

    /**
     * @Route("/oneFilm/{id}", name="one_film")
     */
    public function oneFilm(Films $film = null, ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $film = $entityManager->getRepository(Films::class)->find($id);

        return $this->render(
            'films/oneFilm.html.twig',
            [
                "film" => $film,
            ]
        );
    }
}
