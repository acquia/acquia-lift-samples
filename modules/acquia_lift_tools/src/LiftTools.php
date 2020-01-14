<?php

namespace Drupal\acquia_lift_tools;

use Acquia\Hmac\Guzzle\HmacAuthMiddleware;
use Acquia\Hmac\Key;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;

/**
 * Class LiftTools.
 */
class LiftTools implements LiftToolsInterface {
  /**
   * Utility function to send a request to the Lift Web API.
   *
   * @param string $account_id
   *   Lift Account ID.
   * @param string $api_key
   *   Lift Account API ID.
   * @param string $secret_key
   *   Lift Account API secret key.
   * @param string $method
   *   The request method (ex: GET, POST, DELETE)
   * @param string $api_url
   *   The Lift API url.
   * @param string $path
   *   The request path (ex: customer_sites)
   * @param mixed $body
   *   The request body, which will be encoded as JSON.
   * @param bool $use_auth
   *   Whether or not HMAC authentication is used.
   *
   * @return mixed
   *   The decoded response from the server.
   */
  public static function webRequest($account_id, $api_key, $secret_key, $method, $api_url, $path, $body = [], $use_auth) {

    $handlers = HandlerStack::create();

    if ($use_auth) {
      $key = new Key($api_key, $secret_key);
      $middleware = new HmacAuthMiddleware($key);

      $handlers->push($middleware);
    }

    $client = \Drupal::httpClient();

    $options = [
      'json' => $body,
      'handler' => $handlers,
    ];

    $url = $api_url . '/' . $account_id . '/' . $path;
    $request = new Request($method, $url);

    $response = $client->send($request, $options);
    $body = $response->getBody();
    return json_decode($body, TRUE);
  }

}
