<?php

namespace App\Form;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Entity\localisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BilletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('chair_billet')
            ->add('voyage_num')
            ->add('terminal')
            ->add('portail')
            ->add('embarquement',DateType::class)
          // ->add('localisation')
            ->add('localisation',EntityType::class,[
                'class' => Localisation::class,
                'choice_label' => 'positionArivee_planning',
                 'label' => 'Localisation']
                 )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Billet::class,
        ]);
    }
}
