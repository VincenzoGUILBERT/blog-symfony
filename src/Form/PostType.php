<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function __construct(private Security $security) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setAuthorAndDate(...))
        ;
    }

    public function setAuthorAndDate(FormEvent $formEvent): void
    {
        $post = $formEvent->getData();

        if (!$post->getId()) {

            $post->setAuthor($this->security->getUser())
                ->setCreatedAt(new \DateTimeImmutable())
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
