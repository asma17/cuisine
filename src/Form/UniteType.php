<?php

namespace App\Form;

use App\Entity\Unite;
use App\Repository\UniteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UniteType extends AbstractType
{
    private $UniteRepository;
    public function __construct(UniteRepository $UniteRepository)
    { 
        $this->UniteRepository = $UniteRepository;   
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $unites = $this->UniteRepository->findAll(); 

        $builder
            ->add($builder->create('libelle', ChoiceType::class,
            [
               'choices'=>$unites,
               'placeholder' => "Choisir",
               'choice_label' => 'libelle',
               'choice_value' => function ($unites) {
                    return $unites ? $unites->getId() : '';
                }
            ]));            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Unite::class,
        ]);
    }
}
