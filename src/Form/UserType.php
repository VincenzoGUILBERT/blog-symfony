<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserType extends AbstractType
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Your passwords don\'t match.',
                'constraints' => [new Length(['min' => 3, 'minMessage' => 'Your password must be at least 8 characters long'])],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setPasswordAndCreatedAt(...))
        ;
    }

    public function setPasswordAndCreatedAt(FormEvent $event)
    {
        $user = $event->getData();

        if (!$user->getId()  && $user->getPassword()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()))
                ->setCreatedAt(new \DateTimeImmutable())
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
