<?php

namespace App\Form;

use App\Entity\Localisation;
use App\Entity\Planinng;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class LocalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heureDepart_localisation')
            ->add('heureArrivee_loacalisation')
            ->add('positionDepart_localisation')
            ->add('positionArivee_planning')
            ->add('fusee')
            ->add('planning',EntityType::class,[
                'class' => Planinng::class,
                'choice_label' => 'nom_planning',
                'label' => 'Planinng']
                );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
        ]);
    }
}
