<?php

// src/Form/Type/StreetForm.php

namespace App\Form\Type;

use  App\Entity\Subward;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class SubwardForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('SubwardId', TextType::class ,['label' => 'SubwardId' , 'attr' => ['readonly' => true] ]);
        $builder ->add('Subward', TextType::class ,['label' => 'Subward Name',]);
        $builder ->add('WardId', TextType::class,['label' => 'WardId']);
        $builder ->add('Households', IntegerType::class,['label' => 'Households','required' => false,]);
        $builder ->add('Electors', IntegerType::class,['label' => 'Electors','required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Subward::class,
        ));
    }
}
