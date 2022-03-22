<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Billet;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
          ->add('user',EntityType::class,[
                'class' => User::class,
                'choice_label' => 'mail_utilisateur',
                 'label' => 'User']
                 )
                    ->add('billet',EntityType::class,[
                        'class' => Billet::class,
                        'choice_label' => 'id',
                         'label' => 'Billet']
                         )
                  
                      
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
