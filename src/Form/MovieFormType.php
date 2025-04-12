<?php

namespace App\Form;

// use App\Entity\Actor;
use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;






class MovieFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title', TextType::class, [
        'constraints' => [
          new NotBlank([
            'message' => 'title is not empty!',
          ]),
          new Length([
            'min' => 10,
            'max' => 255,
            'minMessage' => 'title cannot be less than 10 characters',
            'maxMessage' => 'title cannot be more than 255 characters'
          ])
        ],
        'required' => false,
        'attr' => array(
          'class' => 'bg-transparent border border-b-2  w-full h-20 text-xl rounded  outline-none',
          'placeholder' => 'Enter the Movie Title',


        ),
        'label' => false

      ])
      ->add('releaseYear', IntegerType::class, [
        'constraints' => [
          new NotBlank([
            'message' => 'release year cannot be empty!'
          ])
        ],
        'required' => false,
        'attr' => array(
          'class' => 'bg-transparent border border-b-2 w-full rounded mt-4 text-xl outline-none ',
          'placeholder' => 'Enter the Release Year',

        ),
        'label' => false
      ])
      ->add('description', TextareaType::class, [
        'constraints' => [
          new NotBlank([
            'message' => 'Description Cannot be empty!'
          ])
        ],
        'required' => false,
        'attr' => array(
          'class' => 'bg-transparent outline-none rounded text-xl border border-b-2 h-30 w-full mt-4',
          'placeholder' => 'Enter the Movie Description'
        ),
        'label' => false
      ])
      ->add('MovieImg', FileType::class, [
        'constraints' => [
          new NotBlank([
            'message' => 'Movie Img cannot be empty'
          ])
        ],

        'required' => false,
        'mapped' => false,
        'attr' => array(
          'class' => 'w-full h-full object-cover'
        ),
        'label' => false
      ])
      // ->add('actors', EntityType::class, [
      //     'class' => Actor::class,
      //     'choice_label' => 'id',
      //     'multiple' => true,
      // ])
    ;

  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Movie::class,
    ]);
  }
}
