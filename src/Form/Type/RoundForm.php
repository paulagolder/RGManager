<?php

// src/Form/Type/RoundForm.php

namespace App\Form\Type;

use App\Entity\Round;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RoundForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('Roundid', TextType::class,['label' => 'RoundId','required' => false,]);
        $builder ->add('Name', TextType::class ,['label' => 'Round Name',]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Round::class,
            'csrf_protection' => false,
        ));
    }
}
