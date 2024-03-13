<?php

namespace App\Controller;

use App\Entity\Part;
use App\Form\PartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PartController extends AbstractController
    {
        /**
         * @Route("/part", name="part_list")
         */
        public function listAction()
        {
            $part = $this->getDoctrine()
                ->getRepository(Part::class)
                ->findAll();
            return $this->render('part/index.html.twig', [
                'part' => $part
            ]);
        }
        /**
         * @Route("/part/view/{id}", name="part_view")
         */
        public function detailsAction($id)
        {
            $part = $this->getDoctrine()
                ->getRepository(part::class)
                ->find($id);
    
            return $this->render('part/view.html.twig', [
                'part' => $part
            ]);
        }
        /**
         * @Route("/part/delete/{id}", name="part_delete")
         */
        public function deleteAction($id)
        {
            $em = $this->getDoctrine()->getManager();
            $part = $em->getRepository(part::class)->find($id);
            $em->remove($part);
            $em->flush();
    
            $this->addFlash(
                'error',
                'Part delete success'
            );
    
            return $this->redirectToRoute('part_list');
        }
        /**
         * @Route("/part/create", name="part_create", methods={"GET","POST"})
         */
        public function createAction(Request $request)
        {
            $part = new part();
            $form = $this->createForm(PartType::class, $part);
    
            if ($this->saveChanges($form, $request, $part)) {
                $this->addFlash(
                    'notice',
                    'part Add success'
                );
    
                return $this->redirectToRoute('part_list');
            }
    
            return $this->render('part/create.html.twig', [
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
         * @Route("/customer/update/{id}", name="part_update")
         */
        public function updateAction($id, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $customer = $em->getRepository(part::class)->find($id);
    
            $form = $this->createForm(PartType::class, $customer);
    
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
