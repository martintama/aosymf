<?php

namespace Aoshido\webBundle\form\EventListener;

use Aoshido\webBundle\form\TemaType;
use Symfony\Component\form\FormEvent;
use Symfony\Component\form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

class AddTemaByMateriaFieldSuscriber implements EventSubscriberInterface {

    private $entityManager;

    public function __construct($entityManager) {
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addTemaForm($form, $idmateria = null) {

        $qb = $this->entityManager
                ->getRepository('AoshidowebBundle:Temas')
                ->createQueryBuilder('t')
                ->where('t.activo=true')
                ->innerJoin('t.materia', 'm')
                ->andWhere('m.activo=true')
                ->andWhere('m=:idmateria')
                ->setParameter('idmateria', $idmateria)
                ->addOrderBy('t.descripcion', 'ASC');

        $temas_filtrados = $qb->getQuery()->getResult();

        /* $temas_filtrados = function (\Doctrine\ORM\EntityRepository $repository) use ($idmateria) {
          $qb = $repository->createQueryBuilder('t')
          ->where('t.activo=true')
          ->innerJoin('t.materia', 'm')
          ->andWhere('m.activo=true')
          ->andWhere('m=:idmateria')
          ->setParameter('idmateria', $idmateria)
          ->addOrderBy('t.descripcion', 'ASC');
          return ($qb->getQuery()->getResult());
          }; */

        $formOptions = array(
            'type' => new TemaType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => 'Tema',
            'required' => true,
            'mapped' => false,
            //'placeholder' => '- Seleccione Tema -',
//'property' => 'descripcion',
            'options' => array(
                'choice33s' => $temas_filtrados
            )
        );

        if ($idmateria) {
            $formOptions['data'] = $idmateria;
        }

        $form->add('idtema', 'collection', $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::getPropertyAccessor();

//$idcarrera = $accessor->getValue($data, 'idcarrera');

        $this->addTemaForm($form);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        $idmateria = array_key_exists('idmateria', $data) ? $data['idmateria'] : null;
        $this->addTemaForm($form, $idmateria);
    }

}
