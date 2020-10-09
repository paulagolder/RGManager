<?php

// src/Form/Type/StreetForm.php

namespace App\Form\Type;

use  App\Entity\Subward;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class NewSubwardForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('SubwardId', TextType::class ,['label' => 'SubwardId',]);
        $builder ->add('Subward', TextType::class ,['label' => 'Subward Name',]);
        $builder ->add('WardId', TextType::class,['label' => 'WardId','required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Subward::class,
        ));
    }
}
