<?php

namespace Aoshido\webBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

class AddCarreraFieldSuscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    private function addCarreraForm($form) {
        $formOptions = array(
            'class' => 'AoshidowebBundle:Carrera',
            'label' => 'Carrera:',
            'mapped' => false,
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'property' => 'descripcion',
            'empty_value' => '- Seleccione Carrera -',
        );

        $form->add('idcarrera', 'entity', $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::getPropertyAccessor();

        //$idcarrera = $accessor->getValue($data, 'idcarrera');

        $this->addCarreraForm($form);
    }

    public function preSubmit(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        //$idcarrera = array_key_exists('idcarrera', $data) ? $data['idcarrera'] : null;
        $this->addCarreraForm($form);
    }

}
