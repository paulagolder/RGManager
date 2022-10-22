<?php

// src/Form/Type/PDForm.php

namespace App\Form\Type;

use  App\Entity\Pollingdistrict;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class PDForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('PdId', TextType::class,['label' => 'PdId']);
        $builder ->add('PdTag', TextType::class,['label' => 'Tag']);
        $builder ->add('Districtid', TextType::class ,['label' => 'District',]);
         $builder ->add('KML', TextType::class,['label' => 'KML','required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Pollingdistrict::class,
            'csrf_protection' => false,
        ));
    }
}
