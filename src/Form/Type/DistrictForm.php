<?php

// src/Form/Type/SeatForm.php

namespace App\Form\Type;

use  App\Entity\District;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class DistrictForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('DistrictId', TextType::class,['label' => 'DistrictId']);
        $builder ->add('Name', TextType::class ,['label' => 'Name',]);
         $builder ->add('GroupId', TextType::class,['label' => 'Group','required' => false,]);
        $builder ->add('Level', TextType::class ,['label' => 'Level',]);
        $builder ->add('Households', IntegerType::class,['label' => 'Households','required' => false,]);
        $builder ->add('Electors', IntegerType::class,['label' => 'Electors','required' => false,]);
        $builder ->add('KML', TextType::class,['label' => 'KML','required' => false,]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => District::class,
            'csrf_protection' => false,
        ));
    }
}
