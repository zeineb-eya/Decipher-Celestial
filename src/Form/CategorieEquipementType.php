<?php

namespace App\Form;

use App\Entity\CategorieEquipement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Gregwar\CaptchaBundle\Type\CaptchaType;



class CategorieEquipementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_categorie_equipement', ChoiceType::class, [
                'choices' => [
                    'Nourriture' => 'Nourriture',
                    'Vestimentaire' => 'Vestimentaire',
                    'Metalique' => 'Metalique',
                    'Plastique' => 'Plastique',
                    'Eco-friendly' => 'Eco-friendly',
                ],
            ])
            ->add('captcha', CaptchaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategorieEquipement::class,
        ]);
    }
}
