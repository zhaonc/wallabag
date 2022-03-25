<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Graby\Graby;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ArticleSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var Graby $graby */
    private $graby;

    public function __construct(EntityManagerInterface $entityManager, Graby $graby)
    {
        $this->entityManager = $entityManager;
        $this->graby = $graby;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['fetchContent', EventPriorities::PRE_WRITE],
        ];
    }

    public function fetchContent(ViewEvent $event): void
    {
        $article = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$article instanceof Article || Request::METHOD_POST !== $method) {
            return;
        }

        $content = $this->graby->fetchContent($article->getUrl());

        $article->setContent($content['html']);
        $article->setTitle($content['title']);

        $this->entityManager->persist($article);
    }
}