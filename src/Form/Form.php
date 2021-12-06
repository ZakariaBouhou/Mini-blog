<?php

namespace App\Form;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class Form extends AbstractController 
{
    public function newArticle() {
        
        $form = $this->createFormBuilder()
        ->add('pseudo', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'placeholder' => 'Titre de l\'article']
        ])
        ->add('email', EmailType::class, [
            'label' => 'Contenu',
            'attr' => [
                'placeholder' => 'Contenu de l\'article'
            ]
        ])
        ->add('picture', FileType::class, [
            'label' => 'Chargez ici une photo',
            'required' => false,
            'mapped' => false,
        ])
        ->add('add', SubmitType::class, [
            'label' => 'Ajouter l\'article',
        ]) 
        ->getForm();
        
    }
}
