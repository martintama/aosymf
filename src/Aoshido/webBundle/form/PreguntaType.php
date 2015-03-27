<?php

namespace Aoshido\webBundle\Form;

use Aoshido\webBundle\Form\TemaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Aoshido\webBundle\Form\DataTransformer\MateriaToStringTransformer;
use Aoshido\webBundle\Form\EventListener\AddCarreraFieldSuscriber;
use Aoshido\webBundle\Form\EventListener\AddMateriaByCarreraFieldSuscriber;
use Aoshido\webBundle\Form\EventListener\AddTemaByMateriaFieldSuscriber;

class PreguntaType extends AbstractType {



    public function buildForm(FormBuilderInterface $builder, array $options) {

        $entityManager = $options['em'];

         // $materiatransformer = new MateriaToStringTransformer($entityManager);
        $builder->add('contenido', 'text', array(
            'label' => 'Pregunta:',
        ));

        $builder->add('save', 'submit', array(
            'label' => 'Agregar Pregunta',
            'attr' => array(
                'class' => 'btn btn-success'
            ),
        ));

        $builder->addEventSubscriber(new AddCarreraFieldSuscriber());
        $builder->addEventSubscriber(new AddMateriaByCarreraFieldSuscriber());

        //$builder->addEventSubscriber($this->temaSubscriber);
        $builder->addEventSubscriber(new AddTemaByMateriaFieldSuscriber($entityManager));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
                    'data_class' => 'Aoshido\webBundle\Entity\Pregunta',
                ))
                ->setRequired(array(
                    'em',
                ))
                ->setAllowedTypes(array(
                    'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    public function getName() {
        return 'pregunta';
    }

}
