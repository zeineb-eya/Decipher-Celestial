<?php

namespace App\Form;

use App\Entity\Planinng;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PlaninngType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_planning')
            ->add('dateDebut_planning',DateType::class)
            ->add('dateFin_planning',DateType::class)
            ->add('destination_planning')
            ->add('description_planning')
            ->add('periode_planning')
            ->add('prix_planning')
            ->add('imgPlaninng',FileType::class, array('data_class' => null))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planinng::class,
        ]);
    }
}
