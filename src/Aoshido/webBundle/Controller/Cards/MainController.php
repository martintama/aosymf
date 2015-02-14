<?php

namespace Aoshido\webBundle\Controller\Cards;

use Aoshido\webBundle\Entity\Pregunta;
use Aoshido\webBundle\Entity\Respuesta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller {

    public function indexAction(Request $request) {
        $preguntas = $this->getDoctrine()
                ->getRepository('AoshidowebBundle:Pregunta')
                ->findBy(array('activo' => TRUE));

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($preguntas, $this->getRequest()->query->get('page', 1), 8);
        $pagination->setPageRange(6);

        $cantidad = count($preguntas);

        return $this->render('AoshidowebBundle:Cards:index.html.twig', array(
                    'paginas' => $pagination,
                    'cantidad' => $cantidad,
        ));
    }

}
