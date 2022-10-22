<?php

// src/Form/Type/StreetForm.php

namespace App\Form\Type;

use  App\Entity\Rgsubgroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class NewRgsubgroupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('Rgsubgroupid', TextType::class ,['label' => 'RG-SubgroupId',]);
        $builder ->add('Name', TextType::class ,['label' => 'Subgroup Name',]);
        $builder ->add('Rggroupid', TextType::class,['label' => 'RG-GroupId','required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Rgsubgroup::class,
                'csrf_protection' => false,
        ));
    }
}
