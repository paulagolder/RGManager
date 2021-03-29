<?php

// src/Form/Type/DeliveryForm.php

namespace App\Form\Type;

use App\Entity\Delivery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class DeliveryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('Deliveryid', TextType::class,['label' => 'DeliveryId','required' => false,]);
        $builder ->add('Name', TextType::class ,['label' => 'Delivery Name',]);
          $builder ->add('District', TextType::class ,['label' => 'District',]);
            $builder ->add('Seat', TextType::class ,['label' => 'Seat','required' => false,]);
        $builder ->add('TargetDate', TextType::class,['label' => 'Target Date','required' => false,]);
        $builder ->add('Comment', TextType::class,['label' => 'Comment','required' => false,]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Delivery::class,
            'csrf_protection' => false,
        ));
    }
}
