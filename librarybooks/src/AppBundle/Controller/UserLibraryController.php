<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserLibrary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Userlibrary controller.
 *
 * @Route("userlibrary")
 */
class UserLibraryController extends Controller
{
    /**
     * Lists all userLibrary entities.
     *
     * @Route("/", name="userlibrary_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userLibraries = $em->getRepository('AppBundle:UserLibrary')->findAll();

        return $this->render('userlibrary/index.html.twig', array(
            'userLibraries' => $userLibraries,
        ));
    }

    /**
     * Creates a new userLibrary entity.
     *
     * @Route("/new", name="userlibrary_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userLibrary = new Userlibrary();
        $form = $this->createForm('AppBundle\Form\UserLibraryType', $userLibrary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userLibrary);
            $em->flush();

            return $this->redirectToRoute('userlibrary_show', array('id' => $userLibrary->getId()));
        }

        return $this->render('userlibrary/new.html.twig', array(
            'userLibrary' => $userLibrary,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a userLibrary entity.
     *
     * @Route("/{id}", name="userlibrary_show")
     * @Method("GET")
     */
    public function showAction(UserLibrary $userLibrary)
    {
        $deleteForm = $this->createDeleteForm($userLibrary);

        return $this->render('userlibrary/show.html.twig', array(
            'userLibrary' => $userLibrary,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing userLibrary entity.
     *
     * @Route("/{username}/edit", name="userlibrary_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserLibrary $userLibrary)
    {
        $deleteForm = $this->createDeleteForm($userLibrary);
        $editForm = $this->createForm('AppBundle\Form\UserLibraryType', $userLibrary);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('userlibrary_edit', array('username' => $userLibrary->getUsername()));
        }

        return $this->render('userlibrary/edit.html.twig', array(
            'userLibrary' => $userLibrary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a userLibrary entity.
     *
     * @Route("/{id}", name="userlibrary_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserLibrary $userLibrary)
    {
        $form = $this->createDeleteForm($userLibrary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userLibrary);
            $em->flush();
        }

        return $this->redirectToRoute('userlibrary_index');
    }

    /**
     * Creates a form to delete a userLibrary entity.
     *
     * @param UserLibrary $userLibrary The userLibrary entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserLibrary $userLibrary)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userlibrary_delete', array('id' => $userLibrary->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
