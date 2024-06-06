<?php

namespace App\Form;

use App\Entity\Modules;
use App\Entity\Formations;
use App\Entity\Programme;
use App\Entity\Stagiaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('nbPlace',NumberType::class)
            ->add('stagiaires', EntityType::class, [
                'class' => Stagiaires::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
            ->add('programmes', EntityType::class, [
                'class' => Programme::class,
                'choice_label' => function (Programme $programme) {
                    return $this->getModuleLabel($programme);
                },
                'multiple' => true,
            ])
            
            ->add('Valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formations::class,
        ]);
    }
    private function getModuleLabel(Programme $programme): string
    {
        // Assuming you have a relation between Programme and Module entities
        $module = $programme->getModule();
        
        // Return the name of the module
        return $module->getNom();
    }
    
}
