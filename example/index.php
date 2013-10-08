<?php

require_once 'vendor/autoload.php';

use fkooman\OAuth\Client\ClientConfig;
use fkooman\OAuth\Client\SessionStorage;
use fkooman\OAuth\Client\Api;
use fkooman\OAuth\Client\Context;

use fkooman\Guzzle\Plugin\BearerAuth\BearerAuth;
use fkooman\Guzzle\Plugin\BearerAuth\Exception\BearerErrorResponseException;

use Guzzle\Http\Client;

$apiUri = "http://localhost/oauth/php-oauth/api.php/authorizations/";

$clientConfig = new ClientConfig(
    array(
        "authorize_endpoint" => "http://localhost/oauth/php-oauth/authorize.php",
        "client_id" => "php-oauth-client-example",
        "client_secret" => "f00b4r",
        "token_endpoint" => "http://localhost/oauth/php-oauth/token.php",
    )
);

$tokenStorage = new SessionStorage();
$httpClient = new Client();
$api = new Api("foo", $clientConfig, $tokenStorage, $httpClient);

$context = new Context("john.doe@example.org", "authorizations");

$accessToken = $api->getAccessToken($context);
if (false === $accessToken) {
    /* no valid access token available, go to authorization server */
    header("HTTP/1.1 302 Found");
    header("Location: " . $api->getAuthorizeUri($context));
    exit;
}

try {
    $client = new Client();
    $bearerAuth = new BearerAuth($accessToken->getAccessToken());
    $client->addSubscriber($bearerAuth);
    $response = $client->get($apiUri)->send();
    header("Content-Type: application/json");
    echo $response->getBody();
} catch (BearerErrorResponseException $e) {
    if ("invalid_token" === $e->getBearerReason()) {
        // the token we used was invalid, possibly revoked, we throw it away
        $api->deleteAccessToken($context);
        $api->deleteRefreshToken($context);
        /* no valid access token available, go to authorization server */
        header("HTTP/1.1 302 Found");
        header("Location: " . $api->getAuthorizeUri($context));
        exit;
    }
    throw $e;
} catch (Exception $e) {
    die(sprintf('ERROR: %s', $e->getMessage()));
}
