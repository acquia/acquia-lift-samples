<?php

namespace Drupal\acquia_lift_block\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\acquia_lift_block\LiftClientConnectionManager;

class LiftCaptureEventSubscriber implements EventSubscriberInterface {

  private $connection;

  /**
   * Part of the DependencyInjection magic happening here.
   */
  public function __construct(LiftClientConnectionManager $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onRequest', 30];
    return $events;
  }

  public function onRequest(GetResponseEvent $event) {
    $current_uri = $event->getRequest()->getUri();
    $segments = $this->connection->setCurrentSegments($current_uri);
    foreach ($segments as $segment) {
      $event->getRequest()->getSession()->set($segment, $segment);
    }
  }

}
