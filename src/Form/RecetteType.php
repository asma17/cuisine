<?php

namespace App\Form;

use App\Entity\Recette;
use App\Form\IngredientType;
use App\Form\IngredientRecetteType;
use App\Entity\Unite;
use App\Entity\Ingredient;
use App\Entity\IngredientRecette ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RecetteType extends AbstractType
{

    protected $requestStack;
    protected $em;
    protected $session;
    protected $IngredientRepository;

    public function __construct( RequestStack $requestStack,EntityManagerInterface $em, SessionInterface $session , IngredientRepository $IngredientRepository)
    { 
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->session = $session;
        $this->IngredientRepository = $IngredientRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add($builder->create('titre', TextType::class,
        [
            'required' => true,
            'attr' =>  ['class' => 'required', 'placeholder'=>'Saisir le titre de la recette']
        ]))
        ->add($builder->create('soustitre', TextType::class,
        [
            'attr' =>  ['placeholder'=>'Saisir le sous titre de la recette'],
            'label' => "Sous titre"
        ]))

        ->add($builder->create('foringredient', EntityType::class, [
            'required' => true,
            'label' => "Ingrédient",
            'placeholder' => 'Choisir',
            'class' => Ingredient::class,
            'query_builder' => function (IngredientRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            }
            ]))

        ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) { 
            $data = $_POST;
            $tab = [];
            foreach($data as $key=>$value){
                if (strpos($key, 'ingredient') !== false) {
                    array_push($tab, $data[$key]);
                }
            }
            $this->session->set('ingredients', $tab);
        })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}