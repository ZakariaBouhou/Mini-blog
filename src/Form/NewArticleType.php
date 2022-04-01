<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NewArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Titre de l\'article']
        ])
        ->add('content', TextType::class, [
            'label' => 'Contenu',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Contenu de l\'article'
            ]
        ])
        ->add('image', FileType::class, [
            'label' => 'Chargez ici une photo',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Merci d\'insérer un format d\'image valide (jpeg, jpg, png, gif, bmp...)',
                ])
            ]
        ])
        ->add('categoryid', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'label' => 'Choix de la catégorie',
            'multiple' => true,
            'expanded' => true,
        ])
        ->add('add', SubmitType::class, [
            'label' => 'Ajouter l\'article',
            'attr' => [
                'class' => 'btn-primary'
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
