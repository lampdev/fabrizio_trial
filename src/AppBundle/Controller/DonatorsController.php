<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Donators;
use AppBundle\Form\DonatorsType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;

class DonatorsController extends Controller
{

    /**
     * @Route("/", name="list")
     */
    public function listAction(Request $request)
    {
        $allTimeAmount = 0;
        $lastMonthAmount = 0;
        $from = date('Y-m-d h:i:s', strtotime('-1 month'));
        $to = date('Y-m-d h:i:s');
        $em = $this->getDoctrine()->getManager();
        $entManager = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM AppBundle:Donators a";
        $query = $entManager->createQuery($dql);

//        $allDonations = $em->getRepository('AppBundle:Donators')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        $topDonator = $em->getRepository('AppBundle:Donators')
            ->findBy(array(), array('amount' => 'DESC'), 1);
        $lastMonthDonations = $this->getDoctrine()
            ->getRepository(Donators::class)
            ->getDonationsLastMonth($from, $to);

        foreach($lastMonthDonations as $lastMonthDonation) {
            $lastMonthAmount += $lastMonthDonation->getAmount();
        }
        $allTimeDonations = $em->getRepository('AppBundle:Donators')->findAll();

        foreach($allTimeDonations as $allTimeDonation) {
            $allTimeAmount += $allTimeDonation->getAmount();
        }
        return $this->render('AppBundle:Donators:list.html.twig', array(
            'top_donator'       => $topDonator,
            'all_time_amount'   => $allTimeAmount,
            'last_month_amount' => $lastMonthAmount,
            'all_donations' =>  $pagination
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
            $donator->setCreatedAt(new \DateTime('NOW'));

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
     * @Route("/stats", name="stats")
     *
     */
    public function statsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $donations = $em->getRepository(Donators::class)->getDonationsByDay();
        foreach ($donations as $donation) {
            $jsonDonations[] = [
              'date' => $donation['DATE(created_at)'],
              'amount' => intval($donation['SUM(amount)'])
            ];
        }
        return new JsonResponse($jsonDonations);
    }

}
