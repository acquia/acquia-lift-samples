<?php

namespace Drupal\acquia_lift_block\Plugin\Condition;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\acquia_lift_block\LiftClientConnectionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Lift Segment' condition.
 *
 * @Condition(
 *   id = "acquia_lift_block_segment",
 *   label = @Translation("Lift Segment")
 * )
 */
class LiftSegment extends ConditionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The lift client connection manager.
   *
   * @var \Drupal\acquia_lift_block\LiftClientConnectionManager
   */
  protected $clientManager;

  /**
   * LiftSegment constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\acquia_lift_block\LiftClientConnectionManager $client_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LiftClientConnectionManager $client_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->clientManager = $client_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('acquia_lift_block.client_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $account_segments = $this->clientManager->getAccountSegments();

    $form['segments'] = [
      '#type' => 'checkboxes',
      '#title' => t('Select a Lift Segment'),
      '#options' => $account_segments,
      '#default_value' => $this->configuration['segments'] ?? [],
      '#multiple' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['segments'] = array_filter($form_state->getValue('segments'));
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    if (empty($this->configuration['segments']) && !$this->isNegated()) {
      return TRUE;
    }

    $current_segments = $this->clientManager->getCurrentSegments();
    //drupal_set_message($this->t('<pre>@foo</pre>', ['@foo' => var_export($current_segments, TRUE)]));
    //drupal_set_message($this->t('<pre>@foo</pre>', ['@foo' => var_export($this->configuration['segments'], TRUE)]));

    foreach ($this->configuration['segments'] as $segment) {
      //drupal_set_message($this->t('<pre>Array Search: @foo in @bar returns @baz</pre>', ['@foo' => var_export($this->configuration['segments'], TRUE), '@bar' => var_export($current_segments, TRUE), '@baz' => var_export(array_search($segment, $current_segments) !== FALSE, TRUE)]));
      if (array_search($segment, $current_segments) !== FALSE) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    // TODO: Implement summary() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $tags = $this->configuration['segments'];
    $tags[] = 'liftblock';
    return Cache::mergeTags(parent::getCacheTags(), $tags);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = [
      'url.query_args',
    ];
    foreach ($this->configuration['segments'] as $segment) {
      $contexts[] = "acquia_lift_block:$segment";
    }
    return Cache::mergeContexts($contexts, parent::getCacheContexts());
  }

}
