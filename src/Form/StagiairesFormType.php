<?php

namespace App\Form;

use App\Entity\Formations;
use App\Entity\Stagiaires;
use PharIo\Manifest\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiairesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('prenom',TextType::class)
            ->add('dateNaissance',DateType::class, [
                'widget' => 'single_text',
                
            ])
            ->add('sexe',TextType::class)
            ->add('ville',TextType::class)
            ->add('telephone',TextType::class)
            ->add('email',EmailType::class)
            ->add('formations', EntityType::class, [
                'class' => Formations::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
            ->add('Valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaires::class,
        ]);
    }
}
