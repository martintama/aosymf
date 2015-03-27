<?php
/**
 * Created by PhpStorm.
 * User: mtejada
 * Date: 27/03/2015
 * Time: 12:05
 */

namespace Aoshido\webBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Aoshido\webBundle\Form\DataTransformer\TemaToStringTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemaSelectType extends AbstractType

{
    /**
     * @var ObjectManager
     */
    private $om;

    private $choices;

    /**
     * @param ObjectManager $om
     */
    public function __construct(EntityManager $om)
    {
        $this->om = $om;

        // Build our choices array from the database

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $temas = $this->om->getRepository('AoshidowebBundle:Tema')->findAll();

        //$idmateria = array_key_exists('idmateria', $data) ? $data['idmateria'] : null;
        foreach ($temas as $tema) {
            // choices[key] = label
            $this->choices[$tema->getId()] = $tema->getDescripcion();
        }
        $transformer = new TemaToStringTransformer($this->om);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "choices" => $this->choices,
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'tema_select';
    }
}