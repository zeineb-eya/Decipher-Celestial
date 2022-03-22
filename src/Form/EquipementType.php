<?php

namespace App\Form;

use App\Entity\Equipement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Gregwar\CaptchaBundle\Type\CaptchaType;



class EquipementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_equipement')
            ->add('etat_equipement', ChoiceType::class, [
                'choices' => [
                    'new' => 'new',
                    'used' => 'used',
                ],
            ])
            ->add('description_equipement')
            ->add('categorieEquipement')
            ->add('image_equipement',FileType::class, [
                'label' =>false,
                'required' => true,
                             'data_class' => null
                            

                ])
                ->add('captcha', CaptchaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipement::class,
        ]);
    }
}
