<?php

// src/Form/Type/StreetForm.php

namespace App\Form\Type;

use  App\Entity\Street;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class StreetForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('name', TextType::class ,['label' => 'Street Name',]);
        $builder ->add('Part', TextType::class,['label' => 'Part Road','required' => false,]);
        $builder ->add('Qualifier', TextType::class,['label' => 'Description','required' => false,'empty_data'    => '',]);
        $builder ->add('PdId', TextType::class,['label' => 'Polling District','required' => false,]);
        $builder ->add('Households', IntegerType::class,['label' => 'Households','required' => false,]);
        $builder ->add('Electors', IntegerType::class,['label' => 'Electors','required' => false,]);
        //$builder ->add('Updated', HiddenType::class,['label' => 'Updated','required' => false, 'disabled' => true,]);
        $builder ->add('Path', TextType::class,['label' => 'Path','required' => false,]);
        $builder ->add('Note', TextAreaType::class,['label' => 'Comment','required' => false,]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Street::class,
            'csrf_protection' => false,
        ));
    }
}
