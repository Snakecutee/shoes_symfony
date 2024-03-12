<?php

namespace App\Controller;


use App\Entity\Customer;
use App\Form\CustomerAddType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer_list")
     */
    public function listAction()
    {
        $customer = $this->getDoctrine()
            ->getRepository(customer::class)
            ->findAll();
        return $this->render('customer/index.html.twig', [
            'customer' => $customer
        ]);
    }
    /**
     * @Route("/customer/view/{id}", name="customer_view")
     */
    public function detailsAction($id)
    {
        $customer = $this->getDoctrine()
            ->getRepository(customer::class)
            ->find($id);

        return $this->render('customer/view.html.twig', [
            'customer' => $customer
        ]);
    }
    /**
     * @Route("/customer/delete/{id}", name="customer_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(customer::class)->find($id);
        $em->remove($customer);
        $em->flush();

        $this->addFlash(
            'error',
            'Customer delete success'
        );

        return $this->redirectToRoute('customer_list');
    }
    /**
     * @Route("/customer/create", name="customer_create", methods={"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $customer = new customer();
        $form = $this->createForm(CustomerAddType::class, $customer);

        if ($this->saveChanges($form, $request, $customer)) {
            $this->addFlash(
                'notice',
                'Customer Add success'
            );

            return $this->redirectToRoute('customer_list');
        }

        return $this->render('customer/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function saveChanges($form, $request, $customer)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return true;
        }
        return false;
    }
    /**
     * @Route("/customer/update/{id}", name="customer_update")
     */
    public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(customer::class)->find($id);

        $form = $this->createForm(CustomerAddType::class, $customer);

        if ($this->saveChanges($form, $request, $customer)) {
            $this->addFlash(
                'notice',
                'Customer update success'
            );
            return $this->redirectToRoute('customer_list');
        }

        return $this->render('customer/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
