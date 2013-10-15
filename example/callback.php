<?php

require_once 'vendor/autoload.php';

use fkooman\OAuth\Client\ClientConfig;
use fkooman\OAuth\Client\SessionStorage;
use fkooman\OAuth\Client\Callback;
use fkooman\OAuth\Client\AuthorizeException;

use Guzzle\Http\Client;

$clientConfig = new ClientConfig(
    array(
        "authorize_endpoint" => "http://localhost/oauth/php-oauth/authorize.php",
        "client_id" => "php-oauth-client-example",
        "client_secret" => "f00b4r",
        "token_endpoint" => "http://localhost/oauth/php-oauth/token.php",
    )
);

try {
    $tokenStorage = new SessionStorage();
    $httpClient = new Client();
    $cb = new Callback("foo", $clientConfig, $tokenStorage, $httpClient);
    $context = new Context(
        "john.doe@example.org",
        array(
            "authorizations"
        )
    );
    $cb->handleCallback($_GET, $context);

    header("HTTP/1.1 302 Found");
    header("Location: http://localhost/php-oauth-client-example/index.php");
    exit;
} catch (AuthorizeException $e) {
    // this exception is thrown by Callback when the OAuth server returns a
    // specific error message for the client, e.g.: the user did not authorize
    // the request
    die(sprintf("ERROR: %s, DESCRIPTION: %s", $e->getMessage(), $e->getDescription()));
} catch (Exception $e) {
    // other error, these should never occur in the normal flow
    die(sprintf("ERROR: %s", $e->getMessage()));
}
