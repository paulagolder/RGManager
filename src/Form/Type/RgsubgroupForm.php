<?php

// src/Form/Type/RgsubgroupForm.php

namespace App\Form\Type;

use  App\Entity\Rgsubgroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RgsubgroupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('Rgsubgroupid', TextType::class ,['label' => 'RG_SubgroupId' , 'attr' => ['readonly' => true] ]);
        $builder ->add('Name', TextType::class ,['label' => 'Subgroup Name',]);
        $builder ->add('Rggroupid', TextType::class,['label' => 'RG-GroupId']);
        $builder ->add('Households', IntegerType::class,['label' => 'Households','required' => false,]);
        $builder ->add('Electors', IntegerType::class,['label' => 'Electors','required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Rgsubgroup::class,
        ));
    }
}
