<?php

namespace Drupal\acquia_lift_block\Cache;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\HttpFoundation\RequestStack;

class AcquiaLiftBlockContext implements CacheContextInterface {

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $stack;


  /**
   * LiftBlocksContext constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $stack
   */
  public function __construct(RequestStack $stack) {
    $this->stack = $stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return new TranslatableMarkup("Lift Segments Cache.");
  }

  /**
   * {@inheritdoc}
   */
  public function getContext($segment = NULL) {
    if (!$segment) {
      return '';
    }
    if ($this->stack->getCurrentRequest()->getSession()->has($segment)) {
      return $this->stack->getCurrentRequest()->getSession()->get($segment);
    }
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }

}
