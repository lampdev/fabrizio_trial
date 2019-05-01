<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Donators;
use AppBundle\Form\DonatorsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;

class DonatorsController extends Controller
{

    /**
     * @Route("/", name="list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $donators = $em->getRepository('AppBundle:Donators')->findBy(array(), array('amount' => 'DESC'), 1);

        return $this->render('AppBundle:Donators:list.html.twig', array(
            'donators' => $donators
        ));
    }

    /**
     * @Route("/create/{id}", defaults={"id"=null}, name="create")
     */
    public function createAction(Request $request)
    {
        if(($id = $request->get('id'))) {
            $em = $this->getDoctrine()->getManager();
            $donator = $em->getRepository('AppBundle:Donators')->findOneBy([
                'id' => $id
            ]);
        } else {
            $donator = new Donators();
        }
        $form = $this->createForm(DonatorsType::class, $donator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donator = $form->getData();


            $em = $this->getDoctrine()->getManager();
            $em->persist($donator);
            $em->flush();

            $this->addFlash('success', 'Thanks for Donation!');

            return $this->redirectToRoute('list');
        }

        return $this->render('AppBundle:Donators:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/view/{id}", name="view")
     */
    public function viewAction(Request $request)
    {
        if(($id = $request->get('id'))) {
            $em = $this->getDoctrine()->getManager();
            $person = $em->getRepository('AppBundle:Person')->findOneBy([
                'id' => $id
            ]);
        } else {
            return $this->redirectToRoute('list');
        }

        return $this->render('AppBundle:Donators:view.html.twig', array(
            'person' => $person
        ));
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Request $request)
    {
        if(($id = $request->get('id'))) {
            $em = $this->getDoctrine()->getManager();
            $person = $em->getRepository('AppBundle:Person')->findOneBy([
                'id' => $id
            ]);

            $em->remove($person);
            $em->flush();
        }
        $this->addFlash('success', 'Person has been successfully deleted.');

        return $this->redirectToRoute('list');
    }

}
