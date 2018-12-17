<?php

namespace CoffeeShopBundle\Form;

use CoffeeShopBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('category', EntityType::class,[
                'class'=>Category::class,
                'choice_label'=>'name',
                'placeholder'=>'Choose a category'
            ])
            ->add('description', TextareaType::class)
            ->add('price', MoneyType::class,
                [
                    "currency" => "EUR"
                ])
            ->add('quantity', IntegerType::class)
            ->add('image');

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoffeeShopBundle\Entity\Product'
        ));
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function getBlockPrefix()
//    {
//        return 'coffeeshopbundle_product';
//    }


}
