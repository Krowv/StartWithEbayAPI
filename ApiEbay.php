<?php

class ApiEbay
{
    public $devID;
    public $appID;
    public $certID;
    public $clientID;
    public $serverUrl;
    public $userToken;
    public $paypalEmailAddress;
    public $ruName;



    public function __construct()
    {
        $this->userToken = '<Your user token>';
        $this->devID = '<Your devID>';
        $this->appID = '<Your appID>';
        $this->certID = '<Your certID>';
        $this->clientID = '<Your clientID>';
        //set the Server to use (Sandbox or Production)
        $this->serverUrl = 'https://api.ebay.com/ws/api.dll';      // server URL different for prod and sandbox
        //the token representing the eBay user to assign the call with

        $this->authCode = "<Your authCode>";
        $this->authToken ="";
        $this->refreshToken ="<Your refreshCode>";
        $this->ruName= "<Your ruName>";

    }
    // In the case the token is no more avaiable, make a call in a php file to the firstAuthAppToken method.
    // Get the result and put it in the browser.
    // You'll be redirect automatically to an other page. Grab the $_GET['code'] and paste it to your authCode attribut.
    // Make an echo or var_dump() of the authorizationToken to get the refreshToken, then, paste it to the refreshToken attribut.
    // Congratz, your API is ready to use.
    public function firstAuthAppToken() {
        $url = "https://auth.ebay.com/oauth2/authorize?client_id=".$this->clientID."&response_type=code&redirect_uri=".$this->ruName."&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly";
        return $url;
    }

    public function authorizationToken()
    {
        $link = "https://api.ebay.com/identity/v1/oauth2/token";
        $codeAuth = base64_encode($this->clientID.':'.$this->certID);
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '.$codeAuth
        ));
        curl_setopt($ch, CURLHEADER_SEPARATE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&code=".$this->authCode."&redirect_uri=".$this->ruName);
        $response = curl_exec($ch);
        $json = json_decode($response, true);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if($json != null)
        {
            var_dump($json);
            $this->authToken = $json["access_token"];
            $this->refreshToken = $json["refresh_token"];
        }
        return "AuthToken : " . $this->authToken . " / Refresh Token : " . $this->refreshToken;
    }

    public function refreshToken()
    {
        $link = "https://api.ebay.com/identity/v1/oauth2/token";
        $codeAuth = base64_encode($this->clientID.':'.$this->certID);
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '.$codeAuth
        ));
        curl_setopt($ch, CURLHEADER_SEPARATE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=refresh_token&refresh_token=".$this->refreshToken."&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly");
        $response = curl_exec($ch);
        $json = json_decode($response, true);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if($json != null)
        {
            $this->authToken = $json["access_token"];
        }
    }

}