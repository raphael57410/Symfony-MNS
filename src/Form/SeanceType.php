<?php

namespace App\Form;

use App\Entity\Films;
use App\Entity\Salle;
use App\Entity\Seance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut')
            ->add('language')
            ->add(
                'film',
                EntityType::class,
                [
                    "class" => Films::class,
                    "choice_label" => "title"
                ]
            )->add(
                "Salle",
                EntityType::class,
                [
                    "class" => Salle::class,
                    "choice_label" => "numero"
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
