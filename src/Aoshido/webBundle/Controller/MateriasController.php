<?php

namespace Aoshido\webBundle\Controller;

use Aoshido\webBundle\Entity\Materia;
use Aoshido\webBundle\Form\MateriaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MateriasController extends Controller {

    public function newAction(Request $request) {

        //Display a list of all Materias
        $materias = $this->getDoctrine()
                ->getRepository('AoshidowebBundle:Materia')
                ->createQueryBuilder('m')
                ->where('m.activo = TRUE')
                ->getQuery();

        $temasMaterias = array();
        foreach ($materias as $materia) {
            $temasMaterias[$materia->getId()] = count($materia->getTemas());
        }

        $materia = new Materia();
        $form = $this->createForm(new MateriaType(), $materia);

        $form->handleRequest($request);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($materias, $this->getRequest()->query->get('page', 1), 5);
        $pagination->setPageRange(6);

        if ($form->isValid()) {
            $materia->setActivo(TRUE);

            $em = $this->getDoctrine()->getManager();
            $em->persist($materia);
            $em->flush();

            return $this->redirect($this->generateUrl('abms_materias'));
        }

        return $this->render('AoshidowebBundle:Materias:new.html.twig', array(
                    'form' => $form->createView(),
                    'paginas' => $pagination,
                    'temasMaterias' => $temasMaterias,
        ));
    }

    public function editAction(Request $request, $idMateria) {

        //Display a list of all Materias
        $materias = $this->getDoctrine()
                ->getRepository('AoshidowebBundle:Materia')
                ->findBy(array('activo' => TRUE));

        $temasMaterias = array();
        foreach ($materias as $materia) {
            $temasMaterias[$materia->getId()] = count($materia->getTemas());
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($materias, $this->getRequest()->query->get('page', 1), 5);
        $pagination->setPageRange(6);

        $cantidad = count($materias);

        $materia = $this->getDoctrine()
                ->getRepository('AoshidowebBundle:Materia')
                ->find($idMateria);

        $form = $this->createForm(new MateriaType(), $materia);

        $form->handleRequest($request);
        //dump($materia);
        //die();
        if ($form->isValid()) {
            $materia->setActivo(TRUE);

            $em = $this->getDoctrine()->getManager();
            $em->persist($materia);
            $em->flush();

            return $this->redirect($this->generateUrl('abms_materias'));
        }

        return $this->render('AoshidowebBundle:Materias:new.html.twig', array(
                    'form' => $form->createView(),
                    'paginas' => $pagination,
                    'cantidad' => $cantidad,
                    'temasMaterias' => $temasMaterias,
        ));
    }

    public function disableAction($idMateria) {
        $em = $this->getDoctrine()->getManager();

        $materia = $this->getDoctrine()
                ->getRepository('AoshidowebBundle:Materia')
                ->find($idMateria);
        
        foreach ($materia->getTemas() as $tema){
            $materia->removeTema($tema);
            $em->persist($tema);
        }
        
        $materia->setActivo(FALSE);
        $em->persist($materia);
        $em->flush();

        return $this->redirect($this->generateUrl('abms_materias'));
    }

}
