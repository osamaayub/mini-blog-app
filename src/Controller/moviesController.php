<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class moviesController extends AbstractController
{
  private $em;
  private $movieRepository;
  public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository)
  {
    $this->movieRepository = $movieRepository;
    $this->em = $em;
  }
  //All movies get through  database
  #[Route('/movies', name: 'movies')]

  public function index(): Response
  {
    //find all the movies from the database
    $movies = $this->movieRepository->findAll();

    return $this->render('movies/index.html.twig', [
      'movies' => $movies,
    ]);
  }
  //Single movie getting through id
  #[Route('movies/{id}', name: 'single_movie', methods: ['GET'], requirements: ['id' => '\d+'])]
  public function showSingleMovie($id): Response
  {
    //show single  Entity based on the primary key 
    $movie = $this->movieRepository->find($id);
    return $this->render('movies/show.html.twig', [
      'movie' => $movie
    ]);

  }
  // create new movie Route
  #[Route('/movies/create', name: 'create_movie')]
  public function createMovie(Request $request): Response
  {
    //create a new movie instance
    $movie = new Movie();
    //create a Form using the movie entity and FormType Movie
    $form = $this->createForm(MovieFormType::class, $movie);
    //handle the request and check if the form is submited or not
    $form->handleRequest($request);
    //check if the form is submitted and has valid attributes
    if ($form->isSubmitted() && $form->isValid()) {
      //this hold the submitted values by the form
      $newMovie = $form->getData();
      $movieImage = $form->get('MovieImg')->getData();
    
      //if the movie Image path is valid or not
      if ($movieImage) {
        $newFileName = uniqid() . '.' . $movieImage->guessExtension();
        try {
          $movieImage->move(
            $this->getParameter('kernel.project_dir') . '/public/uploads',
            $newFileName
          );
        } catch (FileException $e) {
          return new Response($e->getMessage());
        }
        //set the new path of the movie 
        $newMovie->setMovieImg('/uploads/' . $newFileName);
      }

      //add new data

      $this->em->persist($newMovie);
      //save the newly created values in the database
      $this->em->flush();
      return $this->redirectToRoute('movies');

    }
    return $this->render('movies/create.html.twig', [
      'form' => $form->createView()
    ]);

  }
  // Edit Movie Route
  #[Route('/movies/edit/{id}', name: 'edit_movie')]
  public function editMovie($id, Request $request): Response
  {
    //find the movie by its id
    $existingMovie = $this->movieRepository->find($id);
    $form = $this->createForm(MovieFormType::class, $existingMovie);
    $form->handleRequest($request);
    //we want to grab the image 
    $movieImagePath = $form->get('MovieImg')->getData();
    //check if the movie exists or not
    if (!$existingMovie) {
      throw $this->createNotFoundException('Movie not found');

    }
    //if the form is submitted ot not
    if ($form->isSubmitted() && $form->isValid()) {
      //check if the image path is  valid
      if ($movieImagePath) {
        //check if the image path 
        if ($existingMovie->getMovieImg() !== null) {
          //check if the file exists or not
          if (
            file_exists('kernel.project_dir')
            . $existingMovie->getMovieImg()
          ) {
            $this->getParameter('kernel.project_dir') . $existingMovie->getMovieImg();
          }
          ;
          //generate a new unique file name
          $newFilename = uniqid() . '.' . $movieImagePath->guessExtension();
          try {
            $movieImagePath->move(
              $this->getParameter('kernel.project_dir') . '/public/uploads',
              $newFilename
            );
          } catch (FileException $e) {
            return new Response($e->getMessage());
          }
          //set the new image path if existing is not found or edited
          $existingMovie->setMovieImg('/uploads/' . $newFilename);
          //save it  in the database with the new image path
          $this->em->flush();
          //after updating redirect to movies page
          return $this->redirectToRoute('movies');

        }

      }
    } else {
      //if movie Image path does not exists or is not valid
      $existingMovie->setTitle($form->get('title')->getData());
      $existingMovie->setReleaseYear($form->get('releaseYear')->getData());
      $existingMovie->setDescription($form->get('description')->getData());
      //no need to persist here as its already save in the database

      $this->em->flush();
      //updating movie should redirect to home page where data shown
      $this->redirectToRoute('movies');
    }
    return $this->render('movies/edit.html.twig', [
      'movie' => $existingMovie,
      'form' => $form->createView()
    ]);
  }
  //delete Movie  Route
  #[Route('/movies/delete/{id}', name: 'delete_movie')]
  public function deleteMovie($id): Response
  {
    //find movie by id 
    $movie = $this->movieRepository->find($id);
    //if movie does not exists
    if (!$movie) {
      throw $this->createNotFoundException('Movie Not Found By the Specific id');
    }
    //remove that specific movie from the database 
    $this->em->remove($movie);
    //save it the database
    $this->em->flush();

    //redirect to the movies page after deleting the specific movie
    return $this->redirectToRoute('movies');

  }
}




?>

