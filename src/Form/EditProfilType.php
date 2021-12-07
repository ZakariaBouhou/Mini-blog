<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [ 
                'required' => false                           
            ])
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank([ 'normalizer' => 'trim']),
                    new Length([ 'min' => 2]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'required' => 'true',
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer mot de passe'],
                'constraints' => [
                    new NotBlank([ 'normalizer' => 'trim']),
                    new Length([ 'min' => 8]),
                    new Regex([
                        // https://Regexr.com/3bfsi
                        'pattern' => '#(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).#',
                        'message' => 'Le mot de passe doit comporter au minimum une majuscule, une minuscule, un chiffre et un caractère spécial.',
                    ])
                ],
            ])
            ->add('Confirmer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
