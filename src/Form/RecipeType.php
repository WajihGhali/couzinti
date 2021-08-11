<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('Preparation')
            ->add('imageFile',FileType::class,[
                'required'=>true
            ])
        ;
        $builder->add('ingredient', CollectionType::class, [
            'entry_type' => IngredientType::class,
            'entry_options' => ['label' => false],
            'by_reference'       =>false,
            'allow_add'			=> true,
            'allow_delete'		=> true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
