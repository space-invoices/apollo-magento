<?php
namespace Spaceinvoices;

/**
 * Class Spaceinvoices
 *
 * @package Spaceinvoices
 */

class Spaceinvoices
{
    // @var string token to be used for requests.
    public static $accessToken = null;
    // @var string The base URL for the Space invoices API.
    public static $apiBaseUrl = 'https://api.spaceinvoices.com/v1';

    public static $sandboxUrl = 'https://api-test.spaceinvoices.com/v1';
    public static $productionUrl = 'https://api.spaceinvoices.com/v1';


    // @var string|null The version of the Space invoices API to use for requests.
    public static $apiVersion = null;
    const VERSION = '0.0.1';

    /**
     * Gets the accessToken to be used for requests.
     *
     * @return string $accessToken
     */
    public static function getAccessToken()
    {
        return self::$accessToken;
    }

    /**
     * Sets the accessToken to be used for requests.
     *
     * @param string $accessToken
     */
    public static function setAccessToken($accessToken)
    {
        self::$accessToken = $accessToken;
    }

    /**
     *  @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * Sets the base url to be used for requests.
     *
     * @param string $apiBaseUrl
     */
    public static function setBaseUrl($url)
    {
        self::$apiBaseUrl = $url;
    }

    /**
     *  @return string $apiBaseUrl.
     */
    public static function getBaseUrl()
    {
        return self::$apiBaseUrl;
    }

    /**
     * Sets the mode to 'sandbox' or 'production'
     *
     * @param string $mode
     */
    public static function setMode($mode)
    {
        if ($mode === 'sandbox') {
            self::$apiBaseUrl = self::$sandboxUrl;
        } else if ($mode === 'production'){
            self::$apiBaseUrl = self::$productionUrl;
        }
    }

    /**
     *  @return string Returns .
     */
    public static function getMode()
    {
        if (self::$apiBaseUrl === self::$sandboxUrl) {
            return 'sandbox';
        } else if (self::$apiBaseUrl === self::$productionUrl) {
            return 'production';
        }
        return self::$apiBaseUrl;
    }

}