<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Book;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class DefaultController extends Controller
{
  /**
   * Lists 10 book entities and all authors and genres.
   *
   * @Route("/", name="new_books_list")
   * @Method("GET")
   */
  public function listBooksAction()
  {
      $em = $this->getDoctrine()->getManager();
      $repository = $this->getDoctrine()
    ->getRepository(Book::class);


      $query = $repository->createQueryBuilder('b')
    ->orderBy('b.registerDate', 'DESC')
    ->setMaxResults(10)
    ->getQuery();

      $lastBooks = $query->getResult();

      $authors = $em->getRepository('AppBundle:Author')->findAll();
      $genres = $em->getRepository('AppBundle:Genre')->findAll();
      return $this->render('default/index.html.twig', array(
          'books' => $lastBooks,
          'authors' => $authors,
          'genres' => $genres,
      ));
  }

}
