<?php

namespace App\Controller;

use App\Entity\Films;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Repository\SalleRepository;
use App\Repository\SeanceRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilmsController extends AbstractController
{
    /**
     * @Route("/createFilm", name="create_film")
     */
    public function createFilm(ManagerRegistry $doctrine): Response
    {
        // on utilise ManagerRegistry pour intérargire avec la base de données
        $entityManager = $doctrine->getManager();

        // on crée une nouvelle instance de Films
        $film = new Films;

        // on lui attribu des valeurs
        $film->setTitle('Spiderman');
        $film->setDirector('Jon Watts');
        $film->setGender('Action');
        $film->setDescription('Action');
        $film->setDuree(10);
        $film->setStatus('encours');
        $film->setImage('https://ctr.cdnartwhere.eu/sites/demo/files/styles/manual_crop/public/_dent-de-la-mer_0.jpg');

        // on prépare la requete
        $entityManager->persist($film);
        // on envoie la requete en base de données
        $entityManager->flush();

        return new Response('le film a été ajouté avec l\'id' . $film->getId());
    }

    /**
     * @Route("/", name="accueil")
     * @Route("/sortFilms", name="sort_films")
     */
    public function readFilm(ManagerRegistry $doctrine): Response
    {
        // tableau des genres 
        $genre = [];

        // Variable qui nous sert à savoir si il y'a eu un trie des films
        $sort = false;

        // Nombre de film
        $filmNumber = 0;

        if (!empty($_POST['gender'])) {

            // on récupére les films par genre
            $allFilms = $doctrine->getManager()->getRepository(Films::class)->findBy(["gender" => $_POST['gender']]);

            // on passe a true la variable sort pour dire que les films sont trié
            $sort = true;
        } else {

            // on récupére touts les films de la base de données
            $allFilms = $doctrine->getManager()->getRepository(Films::class)->findAll();
        }




        // ### Ancienne condition pour trier ###

        // Condition pour trier les films par genre
        // if (!empty($_POST)) {
        //     foreach ($allFilms as $film) {
        //         if ($film->getGender() == $_POST['gender']) {
        //             array_push($sortFilms, $film);
        //             $sort = true;
        //         }
        //     }

        // Si le tableau n'est pas vide on change la valeur de 
        // tous les films avec la nouvelle valeur des films trié
        //     if (!empty($sortFilms)) {
        //         $allFilms = $sortFilms;
        //     }
        // }
        // ###

        // boucle pour récuperer les genres des films pour
        // pouvoir l'afficher au front
        foreach ($allFilms as $film) {
            if (!in_array($film->getGender(), $genre)) {
                array_push($genre, $film->getGender());
            }
        }

        // Nombre de films au total
        $filmNumber = count($allFilms);


        // on envoie la vue les données de notre base
        return $this->render(
            'films/films.html.twig',
            [
                "films" => $allFilms,
                "genres" => $genre,
                "filmNumber" => $filmNumber,
                "sort" => $sort
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
    public function oneFilm(Films $film = null, SeanceRepository $seanceRepository, SalleRepository $salleRepository): Response
    {

        // on recupére l'id du film 
        $idFilm = $film->getId();

        // on récupère la séance
        $currentSeance = $seanceRepository->findBy(["film" => $idFilm]);

        if (!empty($currentSeance)) {
            $salle = $salleRepository->findBy(["id" => $currentSeance[0]->getSalle()->getId()]);
        } else {
            $salle = null;
        }

        return $this->render(
            'films/oneFilm.html.twig',
            [
                "film" => $film,
                "salle" => $salle
            ]
        );
    }
}
