<?php

namespace AppBundle\Controller;

use PDOException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use AppBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Author controller.
 *
 * @Route("author")
 */
class AuthorController extends Controller
{
    /**
     * Lists all author entities.
     *
     * @Route("/", name="author_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $authors = $em->getRepository('AppBundle:Author')->findAll();


        return $this->render('author/index.html.twig', array(
            'authors' => $authors,
        ));
    }

    /**
     * Creates a new author entity.
     *
     * @Route("/new", name="author_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm('AppBundle\Form\AuthorEditType', $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('author_show', array('id' => $author->getId()));
        }

        return $this->render('author/new.html.twig', array(
            'author' => $author,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a author entity.
     *
     * @Route("/{id}", name="author_show")
     * @Method("GET")
     */
    public function showAction(Author $author)
    {
        $deleteForm = $this->createDeleteForm($author);
        $books = $author->getBooks();

        return $this->render('author/show.html.twig', array(
            'author' => $author,
            'books' => $books,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing author entity.
     *
     * @Route("/{id}/edit", name="author_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Author $author)
    {
        $deleteForm = $this->createDeleteForm($author);
        $editForm = $this->createForm('AppBundle\Form\AuthorEditType', $author);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('author_edit', array('id' => $author->getId()));
        }

        return $this->render('author/edit.html.twig', array(
            'author' => $author,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a author entity.
     *
     * @Route("/{id}", name="author_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Author $author)
    {
        $form = $this->createDeleteForm($author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
              $em->remove($author);
              $em->flush();
            }
            catch (DBALException $e) {
              $this->addFlash(
                'notice',
                'Some book have this author, you cant delete it'
              );
            }
          }

        return $this->redirectToRoute('author_index');
    }

    /**
     * Creates a form to delete a author entity.
     *
     * @param Author $author The author entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Author $author)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('author_delete', array('id' => $author->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
