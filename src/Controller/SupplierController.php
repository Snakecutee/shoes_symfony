<?php

namespace App\Controller;

use App\Entity\Shoes;
use App\Entity\Supplier;
use App\Form\SupplierAddType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupplierController extends AbstractController
{
    /**
     * @Route("/supplier", name="supplier_list")
     */
    public function listAction()
    {
        $supplier = $this->getDoctrine()
            ->getRepository(Supplier::class)
            ->findAll();
        return $this->render('supplier/index.html.twig', [
            'supplier' => $supplier
        ]);
    }

    /**
     * @Route("/supplier/view/{id}", name="supplier_view")
     */
    public
    function ViewAction($id)
    {
        $supplier = $this->getDoctrine()
            ->getRepository(supplier::class)
            ->find($id);

        return $this->render('supplier/view.html.twig', [
            'supplier' => $supplier
        ]);
    }

    /**
     * @Route("/supplier/delete/{id}", name="supplier_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository(supplier::class)->find($id);
        $em->remove($supplier);
        $em->flush();

        $this->addFlash(
            'error',
            'Supplier delete success'
        );

        return $this->redirectToRoute('supplier_list');

    }
    /**
     * @Route("/supplier/create", name="supplier_create", methods={"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $supplier = new supplier();
        $form = $this->createForm(SupplierAddType::class, $supplier);

        if ($this->saveChanges($form, $request, $supplier)) {
            $this->addFlash(
                'notice',
                'Supplier Add success'
            );

            return $this->redirectToRoute('supplier_list');
        }

        return $this->render('supplier/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function saveChanges($form, $request, $supplier)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($supplier);
            $em->flush();

            return true;
        }
        return false;
    }
    /**
     * @Route("/supplier/update/{id}", name="supplier_update")
     */
    public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository(supplier::class)->find($id);

        $form = $this->createForm(SupplierAddType::class, $supplier);

        if ($this->saveChanges($form, $request, $supplier)) {
            $this->addFlash(
                'notice',
                'Supplier update success'
            );
            return $this->redirectToRoute('supplier_list');
        }

        return $this->render('supplier/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

}