<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 4/14/15
 * Time: 15:59
 */

class AuthController extends BaseController {
    public function index() {
        // Call set_include_path() as needed to point to your client library.
        session_start();
        /*
        * You can acquire an OAuth 2.0 client ID and client secret from the
        * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
        * For more information about using OAuth 2.0 to access Google APIs, please see:
        * <https://developers.google.com/youtube/v3/guides/authentication>
        * Please ensure that you have enabled the YouTube Data API for your project.
        */
        $OAUTH2_CLIENT_ID = '164112158546-uifsvkloh5bt2oh992p48vfrsk8tvgkc.apps.googleusercontent.com';
        $OAUTH2_CLIENT_SECRET = 'yJXi8aQJ1N_Djdk0oHAfAFF3';
        $client = new Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
            FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);

        $client->setAccessType("offline");
        $client->setApprovalPrompt('force');
// Define an object that will be used to make all API requests.
        $youtube = new Google_Service_YouTube($client);
        if (isset($_GET['code'])) {
            if (strval($_SESSION['state']) !== strval($_GET['state'])) {
                die('The session state did not match.');
            }
            $client->authenticate($_GET['code']);
            $_SESSION['token'] = $client->getAccessToken();
            header('Location: ' . $redirect);
        }
        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }
// Check to ensure that the access token was successfully acquired.
        if ($client->getAccessToken()) {

            $_SESSION['token'] = $client->getAccessToken();
            file_put_contents("token.txt",$client->getAccessToken());
        } else {
// If the user hasn't authorized the app, initiate the OAuth flow
            $state = mt_rand();
            $client->setState($state);
            $_SESSION['state'] = $state;
            $authUrl = $client->createAuthUrl();
            $htmlBody = "
<h3>Authorization Required</h3>
<p>You need to <a href=".$authUrl.">authorize access</a> before proceeding.<p>
    ";
            echo $htmlBody;
        }
    }
}