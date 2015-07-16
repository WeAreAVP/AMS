<?php

/**
 * Zend_Gdata_HttpClient
 */
require_once 'Zend/Gdata/HttpClient.php';

/**
 * Zend_Version
 */
require_once 'Zend/Version.php';

/**
 * Class to facilitate Google's "Account Authentication
 * for Installed Applications" also known as "ClientLogin".
 * @see http://code.google.com/apis/accounts/AuthForInstalledApps.html
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gdata
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_OAuthClient
{

    const TOKEN_CREDENTIAL_URI = 'https://www.googleapis.com/oauth2/v3/token';

    /**
     * The default 'source' parameter to send to Google
     *
     */
    const DEFAULT_SOURCE = 'Zend-ZendFramework';

    /**
     * Set Google authentication credentials.
     * Must be done before trying to do any Google Data operations that
     * require authentication.
     * For example, viewing private data, or posting or deleting entries.
     *
     * @param string $email
     * @param string $password
     * @param string $service
     * @param Zend_Gdata_HttpClient $client
     * @param string $source
     * @param string $loginToken The token identifier as provided by the server.
     * @param string $loginCaptcha The user's response to the CAPTCHA challenge.
     * @param string $accountType An optional string to identify whether the
     * account to be authenticated is a google or a hosted account. Defaults to
     * 'HOSTED_OR_GOOGLE'. See: http://code.google.com/apis/accounts/docs/AuthForInstalledApps.html#Request
     * @throws Zend_Gdata_App_AuthException
     * @throws Zend_Gdata_App_HttpException
     * @throws Zend_Gdata_App_CaptchaRequiredException
     * @return Zend_Gdata_HttpClient
     */
    public static function getHttpClient($clientId, $clientSecret, $refreshToken,
        $client = null,
        $source = self::DEFAULT_SOURCE,
        $tokenCredentialUri = self::TOKEN_CREDENTIAL_URI)
    {
        if (! ($clientId && $clientSecret && $refreshToken)) {
            require_once 'Zend/Gdata/App/AuthException.php';
            throw new Zend_Gdata_App_AuthException(
                   'Missing Google credentials before trying to ' .
                   'authenticate');
        }

        if ($client == null) {
            $client = new Zend_Gdata_HttpClient();
        }
        if (!$client instanceof Zend_Http_Client) {
            require_once 'Zend/Gdata/App/HttpException.php';
            throw new Zend_Gdata_App_HttpException(
                    'Client is not an instance of Zend_Http_Client.');
        }

        // Build the HTTP client for authentication
        $client->setUri($tokenCredentialUri);
        $useragent = $source . ' Zend_Framework_Gdata/' . Zend_Version::VERSION;
        $client->setConfig(array(
                'maxredirects'    => 0,
                'strictredirects' => true,
                'useragent' => $useragent
            )
        );
        $client->setParameterPost('grant_type', 'refresh_token');
        $client->setParameterPost('refresh_token', (string) $refreshToken);
        $client->setParameterPost('client_id', (string) $clientId);
        $client->setParameterPost('client_secret', (string) $clientSecret);

        // Send the authentication request
        // For some reason Google's server causes an SSL error. We use the
        // output buffer to supress an error from being shown. Ugly - but works!
        ob_start();
        try {
            $response = $client->request('POST');
        } catch (Zend_Http_Client_Exception $e) {
            require_once 'Zend/Gdata/App/HttpException.php';
            throw new Zend_Gdata_App_HttpException($e->getMessage(), $e);
        }
        ob_end_clean();

        // Parse Google's response
	$goog_resp = json_decode($response->getBody());

        if ($response->getStatus() == 200) {
            $client->setBearerToken($goog_resp->{'access_token'});
            $useragent = $source . ' Zend_Framework_Gdata/' . Zend_Version::VERSION;
            $client->setConfig(array(
                    'strictredirects' => true,
                    'useragent' => $useragent
                )
            );
            return $client;

        } else {
            require_once 'Zend/Gdata/App/AuthException.php';
            throw new Zend_Gdata_App_AuthException('Authentication with Google failed. Reason: ' .
                $goog_resp->{'error_description'});
        }
    }

}
