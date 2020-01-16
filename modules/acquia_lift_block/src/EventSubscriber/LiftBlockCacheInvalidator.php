<?php

namespace Drupal\acquia_lift_block\EventSubscriber;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LiftBlockCacheInvalidator implements EventSubscriberInterface {

  private $cacheTagsInvalidator;

  public function __construct(CacheTagsInvalidatorInterface $cache_tags_invalidator) {
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onRequest', 30];
    return $events;
  }

  public function onRequest(GetResponseEvent $event) {
    $this->cacheTagsInvalidator->invalidateTags(['liftblock']);
  }
}
