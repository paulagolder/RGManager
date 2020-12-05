<?php

// src/Form/Type/RggroupForm.php

namespace App\Form\Type;

use App\Entity\Rggroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RggroupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('Rggroupid', TextType::class,['label' => 'RG-GroupId']);
        $builder ->add('Name', TextType::class ,['label' => 'Group Name',]);
        $builder ->add('Households', IntegerType::class,['label' => 'Households','required' => false,]);
        $builder ->add('Electors', IntegerType::class,['label' => 'Electors','required' => false,]);
        $builder ->add('Latitude', TextType::class,['label' =>  'Latitude','required' => false,]);
        $builder ->add('Longitude', TextType::class,['label' => 'Longitude','required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Rggroup::class,
        ));
    }
}
