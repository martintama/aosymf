<?php

namespace Aoshido\webBundle\Form\EventListener;

use Aoshido\webBundle\Form\TemaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AddTemaByMateriaFieldSuscriber implements EventSubscriberInterface
{

    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addTemaForm($form, $idmateria = null)
    {

        //Esto es lo que usabamos cuando era una entity y no una collection
        /*$temas_filtrados = function (\Doctrine\ORM\EntityRepository $repository) use ($idmateria) {
            $qb = $repository->createQueryBuilder('t')
                    ->where('t.activo=true')
                    ->innerJoin('t.materia', 'm')
                    ->andWhere('m.activo=true')
                    ->andWhere('m=:idmateria')
                    ->setParameter('idmateria', $idmateria)
                    ->addOrderBy('t.descripcion', 'ASC');
            return ($qb->getQuery()->getResult());
        };*/

        $qb = $this->entityManager
            ->getRepository('AoshidowebBundle:Tema')
            ->createQueryBuilder('t')
            ->where('t.activo=true')
            ->innerJoin('t.materia', 'm')
            ->andWhere('m.activo=true')
            ->andWhere('m=:idmateria')
            ->setParameter('idmateria', $idmateria)
            ->addOrderBy('t.descripcion', 'ASC');

        $temas_filtrados = $qb->getQuery()->getResult();

        $formOptions = array(
            'type' => 'tema_select',
            'allow_add' => true,
            'allow_delete' => true,
            'label' => 'Tema',
            'required' => true,
            'mapped' => false,
            'options' => (
            array('choices' => $temas_filtrados,

                'property_path' => 'descripcion')
            )

        );

        if ($idmateria) {
            $formOptions['data'] = $idmateria;
        }

        $form->add('idtema', 'collection', $formOptions);
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::getPropertyAccessor();

//$idcarrera = $accessor->getValue($data, 'idcarrera');

        $this->addTemaForm($form);
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $idmateria = array_key_exists('idmateria', $data) ? $data['idmateria'] : null;
        $this->addTemaForm($form, $idmateria);
    }

}
