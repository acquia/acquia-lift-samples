<?php
namespace Drupal\acquia_lift_block;

use Acquia\LiftClient\Entity\Capture;
use Acquia\LiftClient\Entity\CapturePayload;
use Acquia\LiftClient\Lift;
use Drupal\Core\Config\ConfigFactoryInterface;

class LiftClientConnectionManager {

  protected $currentSegments;
  protected $accountSegments = [];
  protected $client;

  /**
   * Content hub configuration.
   *
   * @var \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig
   */
  private $config;

  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->getEditable('acquia_lift.settings');

    if (!$this->config) {
      return;
    }

    $url = $this->config->get('credential.decision_api_url');
    $publicKey = $this->config->get('credential.api_key');
    $secretKey = $this->config->get('credential.secret_key');
    $accountId = $this->config->get('credential.account_id');
    $siteId = $this->config->get('credential.site_id');
    $this->client = new Lift($accountId, $siteId, $publicKey, $secretKey, ['base_url' => $url]);

  }

  public function getCookie() {
    $cookie =\Drupal::request()->cookies->get('Drupal_visitor_tracking');
    if (!$cookie) {
      $permittedChars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $randomTracking = substr(str_shuffle($permittedChars), 0, 22);
      user_cookie_save(['tracking' => $randomTracking]);
      $cookie = $randomTracking;
    }

    return $cookie;
  }

  public function getSession() {
    $cookie = $_COOKIE["touch_id"];
    if (!$cookie) {
      $permittedChars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $randomTracking = substr(str_shuffle($permittedChars), 0, 16);
      setcookie("touch_id", $randomTracking, time() + 1800);
      $cookie = $randomTracking;
    }

    return $cookie;
  }

  public function setCurrentSegments($uri) {
    // Make a decision for a slot.
    $capturePayload = new CapturePayload();
    $capturePayload
      ->setDoNotTrack(false)
      ->setIdentity($this->getCookie())
      ->setTouchIdentifier($this->getSession())
      ->setIdentitySource('tracking')
      ->setReturnSegments(true);
    $capture = new Capture();
    $capture->setUrl($uri);
    $capture->setEventName('Content View');
    $capture->setEventSource('web');
    $userAgent = \Drupal::request()->server->get('HTTP_USER_AGENT');
    if (isset($userAgent)) {
      $capture->setUserAgent($userAgent);
    }
    $capturePayload->setCaptures([$capture]);
    $manager = $this->client->getCaptureManager();
    $response = $manager->add($capturePayload);

    // Get the matched segments.
    $segments = $response->getMatchedSegments();
    foreach ($segments as $segment) {
      $this->currentSegments[] = $segment->getId();
    }
  }

  public function getCurrentSegments() {
    return $this->currentSegments ?? [];
  }

  public function setAccountSegments() {
    // Get all existing segments.
    $manager = $this->client->getSegmentManager();
    $segments = $manager->query();

    // List all segment descriptions.
    foreach ($segments as $segment) {
      $this->accountSegments[$segment->getId()] = $segment->getId();
    }

  }

  public function getAccountSegments() {
    $this->setAccountSegments();
    return $this->accountSegments;
  }

}
