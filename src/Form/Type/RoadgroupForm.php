<?php

// src/Form/Type/StreetForm.php

namespace App\Form\Type;

use  App\Entity\Roadgroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class RoadgroupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder ->add('RoadGroupId', TextType::class,['label' => 'RoadGroupId',]);
        $builder ->add('Name', TextType::class,['label' => 'Name of Group',]);
        $builder ->add('WardId', TextType::class,['label' => 'WardId','required' => false,]);
        $builder ->add('SubwardId', TextType::class,['label' => 'SubwardId','required' => false,]);
        $builder ->add('Households', IntegerType::class,['label' => 'Households','required' => false,]);
        $builder ->add('Electors', IntegerType::class,['label' => 'Electors','required' => false,]);
        $builder ->add('Distance', NumberType::class,['label' => 'Distance(mls)','required' => false,]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Roadgroup::class,
        ));
    }
}
