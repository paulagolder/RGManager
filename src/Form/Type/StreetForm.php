<?php

// src/Form/Type/StreetForm.php

namespace App\Form\Type;

use  App\Entity\Street;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class StreetForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('name', TextType::class ,['label' => 'Street Name',]);
           $builder ->add('Part', TextType::class,['label' => 'Part Road','required' => false,]);
              $builder ->add('Qualifier', TextType::class,['label' => 'Description','required' => false,]);
        $builder ->add('PD', TextType::class,['label' => 'Polling District','required' => false,]);
        $builder ->add('RoadGroupId', TextType::class,['label' => 'RoadGroupId','required' => false,]);
        $builder ->add('WardId', TextType::class,['label' => 'WardId','required' => false,]);
        $builder ->add('SubwardId', TextType::class,['label' => 'SubwardId','required' => false,]);
        $builder ->add('Households', IntegerType::class,['label' => 'Households','required' => false,]);
        $builder ->add('Electors', IntegerType::class,['label' => 'Electors','required' => false,]);
        $builder ->add('Latitude', TextType::class,['label' =>  'Latitude','required' => false,]);
        $builder ->add('Longitude', TextType::class,['label' => 'Longitude','required' => false,]);
            $builder ->add('Note', TextType::class,['label' => 'Comment','required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Street::class,
        ));
    }
}