<?php

namespace App\Controller;

use App\Entity\Shoes;
use App\Form\ShoesCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    class ShoesController extends AbstractController
    {
        /**
         * @Route("/shoes", name="shoes_list")
         */
        public function listAction()
        {
            $shoes = $this->getDoctrine()
                ->getRepository(Shoes::class)
                ->findAll();
            return $this->render('shoes/index.html.twig', [
                'shoes' => $shoes
            ]);
        }
        /**
         * @Route("/shoes/view/{id}", name="shoes_view")
         */
        public function viewAction($id)
        {
            $shoes = $this->getDoctrine()
                ->getRepository(Shoes::class)
                ->find($id);

            return $this->render('shoes/view.html.twig', [
                'shoes' => $shoes
            ]);
        }
        /**
         * @Route("/shoes/delete/{id}", name="shoes_delete")
         */
        public function deleteAction($id)
        {
            $em = $this->getDoctrine()->getManager();
            $shoes = $em->getRepository(shoes::class)->find($id);
            $em->remove($shoes);
            $em->flush();

            $this->addFlash(
                'error',
                'Shoes delete success'
            );

            return $this->redirectToRoute("shoes_list");
        }
        /**
         * @Route("/shoes/create", name="shoes_create", methods={"GET","POST"})
         */
        public function createAction(Request $request)
        {
            $shoes = new shoes();
            $form = $this->createForm(ShoesCreateType::class, $shoes);

            if ($this->saveChanges($form, $request, $shoes)) {
                $this->addFlash(
                    'notice',
                    'Shoes add success'
                );

                return $this->redirectToRoute("shoes_list");
            }

            return $this->render('shoes/create.html.twig', [
                'form' => $form->createView()
            ]);
        }

        public function saveChanges($form, $request, $shoes)
        {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($shoes);
                $em->flush();

                return true;
            }
            return false;
        }

        /**
         * @Route("/shoes/update/{id}", name="shoes_update")
         */
        public function updateAction($id, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $shoes = $em->getRepository(Shoes::class)->find($id);

            $form = $this->createForm(ShoesCreateType::class, $shoes);

            if ($this->saveChanges($form, $request, $shoes)) {
                $this->addFlash(
                    'notice',
                    'Shoes update success'
                );
                return $this->redirectToRoute('shoes_list');
            }

            return $this->render('shoes/update.html.twig', [
                'form' => $form->createView()
            ]);
        }


    }
