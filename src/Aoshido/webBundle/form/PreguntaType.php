<?php

namespace Aoshido\webBundle\form;

use Aoshido\webBundle\form\TemaType;
use Symfony\Component\form\AbstractType;
use Symfony\Component\form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Aoshido\webBundle\Form\DataTransformer\MateriaToStringTransformer;
use Aoshido\webBundle\form\EventListener\AddCarreraFieldSuscriber;
use Aoshido\webBundle\form\EventListener\AddMateriaByCarreraFieldSuscriber;
use Aoshido\webBundle\form\EventListener\AddTemaByMateriaFieldSuscriber;

class PreguntaType extends AbstractType {

    protected $temaSubscriber;

    public function __construct(\Aoshido\webBundle\form\EventListener\AddTemaByMateriaFieldSuscriber $temaSubscriber) {
        $this->temaSubscriber = $temaSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        /* $entityManager = $options['em'];

          $materiatransformer = new MateriaToStringTransformer($entityManager); */
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

        $builder->addEventSubscriber($this->temaSubscriber);
        //$builder->addEventSubscriber(new AddTemaByMateriaFieldSuscriber());
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
