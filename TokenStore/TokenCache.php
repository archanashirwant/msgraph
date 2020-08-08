<?php
namespace App\TokenStore;

class TokenCache {
    public $oauth_env = array(
		'OAUTH_APP_ID' => 'cc492ce4-646f-48a9-96be-421dae38dbf3',
        'OAUTH_APP_PASSWORD'=>'LdJ.c.Uu_56Uh54YK_K_3YX7lP2F~_v0qL',
        'OAUTH_REDIRECT_URI'=>'http://localhost:8080/msgraph/Auth/callback',
        'OAUTH_SCOPES'=>'offline_access https://graph.microsoft.com/.default',
        'OAUTH_AUTHORIZE_ENDPOINT'=>'https://login.microsoftonline.com/0bab8eb0-b94d-4992-aa17-04064768c392/oauth2/v2.0/authorize',
        'OAUTH_TOKEN_ENDPOINT'=>'https://login.microsoftonline.com/0bab8eb0-b94d-4992-aa17-04064768c392/oauth2/v2.0/token',
    );
  
    public function storeTokens($accessToken, $user) {
        $_SESSION['accessToken'] = $accessToken->getToken();
		$_SESSION['refreshToken'] =  $accessToken->getRefreshToken();
		$_SESSION['tokenExpires'] =  $accessToken->getExpires();
		$_SESSION['userName'] =  $user->getDisplayName();
		$_SESSION['userEmail'] =  null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName();
        
    }
    public function clearTokens() {
        unset($_SESSION['accessToken']);
        unset($_SESSION['refreshToken']);
        unset($_SESSION['tokenExpires']);
        unset($_SESSION['userName']);
        unset($_SESSION['userEmail']);
    }
    public function getAccessToken() {
        // Check if tokens exist
        if (empty($_SESSION['accessToken']) || empty($_SESSION['refreshToken']) || empty($_SESSION['tokenExpires'])) {
            return '';
        }
        // Check if token is expired
        //Get current time + 5 minutes (to allow for time differences)
        $now = time() + 300;
        if ($_SESSION['tokenExpires'] <= $now) {
            // Token is expired (or very close to it)
            // so let's refresh
            // Initialize the OAuth client
            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => $this->oauth_env['OAUTH_APP_ID'],
                'clientSecret'            => $this->oauth_env['OAUTH_APP_PASSWORD'],
                'redirectUri'             => $this->oauth_env['OAUTH_REDIRECT_URI'],
                'urlAuthorize'            => $this->oauth_env['OAUTH_AUTHORIZE_ENDPOINT'],
                'urlAccessToken'          => $this->oauth_env['OAUTH_TOKEN_ENDPOINT'],
                'urlResourceOwnerDetails' => '',
                'scopes'                  => $this->oauth_env['OAUTH_SCOPES']
            ]);
            try {
                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => $_SESSION['refreshToken']
                ]);
                // Store the new values
                $this->updateTokens($newToken);
                return $newToken->getToken();
            }
            catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                return '';
            }
        }
        // Token is still valid, just return it
        return $_SESSION['accessToken'];
    }
    public function updateTokens($accessToken) {
        $_SESSION['accessToken'] = $accessToken->getToken();
        $_SESSION['refreshToken'] = $accessToken->getRefreshToken();
        $_SESSION['tokenExpires'] = $accessToken->getExpires();
        
    }
}