<?php 

namespace App\Form\EventSubscriber;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormEvents;

class SetAuthorAndDateSubscriber implements EventSubscriberInterface
{
    public function __construct(private Security $security) {}

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::POST_SUBMIT => 'onPostSubmit'];
    }

    public function onPostSubmit(PostSubmitEvent $event): void
    {
        $data = $event->getData();

        if (!$data->getId()) {

            $data->setAuthor($this->security->getUser())
                ->setCreatedAt(new \DateTimeImmutable())
            ;
        }
    }
}