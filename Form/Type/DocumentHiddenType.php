<?php

namespace Glifery\EntityHiddenTypeBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Glifery\EntityHiddenTypeBundle\Form\DataTransformer\ObjectToIdTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentHiddenType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ObjectToIdTransformer(
            $this->registry,
            $options['dm'],
            $options['class'],
            $options['property']
        );
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['class'])
            ->setDefaults(
                [
                    'data_class' => null,
                    'invalid_message' => 'The document does not exist.',
                    'property' => 'id',
                    'dm' => 'default',
                ]
            )
            ->setAllowedTypes(
                [
                    'invalid_message' => ['null', 'string'],
                    'property'        => ['null', 'string'],
                    'dm'              => ['null', 'string', 'Doctrine\Common\Persistence\ObjectManager'],
                ]
            );
    }

    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\HiddenType';
    }
}