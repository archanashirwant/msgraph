<?php
/*
namespace App\Http\Controllers;

//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
*/ 

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use Microsoft\Graph\Core\GraphConstants;

class AuthController extends Controller
{
    public $oauth_env = array(
        'OAUTH_APP_ID' => 'cc492ce4-646f-48a9-96be-421dae38dbf3',
        'OAUTH_APP_PASSWORD'=>'LdJ.c.Uu_56Uh54YK_K_3YX7lP2F~_v0qL',
        'OAUTH_REDIRECT_URI'=>'http://localhost:8080/msgraph/Auth/callback',
        'OAUTH_SCOPES'=>'offline_access https://graph.microsoft.com/.default',
        'OAUTH_AUTHORIZE_ENDPOINT'=>'https://login.microsoftonline.com/0bab8eb0-b94d-4992-aa17-04064768c392/oauth2/v2.0/authorize',
        'OAUTH_TOKEN_ENDPOINT'=>'https://login.microsoftonline.com/0bab8eb0-b94d-4992-aa17-04064768c392/oauth2/v2.0/token',
    );
    
    public function sign(){

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
        $authUrl = $oauthClient->getAuthorizationUrl();

        // Save client state so we can validate in callback
        $_SESSION['oauthState'] = $oauthClient->getState();

		header("Location: $authUrl");
        // Redirect to AAD signin page
//        return redirect()->away($authUrl);
    }
  
    public function callback($params){


        // Validate state
        $expectedState = $_SESSION['oauthState'];
		$providedState = $params['state'];

	    unset($_SESSION['oauthState']);       

        if (!isset($expectedState) || !isset($providedState) || $expectedState != $providedState) {
			header('Location: /msgraph/Auth/sign');
			$_SESSION['errors']['Invalid auth state'] = 'The provided auth state did not match the expected value';
			//		header("Location: /");
				 /*       return redirect('/')
							->with('error', 'Invalid auth state')
							->with('errorDetail', 'The provided auth state did not match the expected value'); */
        }
  
        // Authorization code should be in the "code" query param
        $authCode = $params['code'];
        if (isset($authCode)) {
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
                // Make the token request
                $accessToken = $oauthClient->getAccessToken('authorization_code', ['code' => $authCode]);
            
                $graph = new Graph();
                $graph->setAccessToken($accessToken->getToken());
            
                $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();		
		
            
                $tokenCache = new TokenCache();
                $tokenCache->storeTokens($accessToken, $user);

				
			    header('Location: /msgraph/Auth/userForm');

            }
            catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
				//header('Location: /msgraph/Auth/sign');
				$this->render("index");
				$_SESSION['errors'] = $e->getMessage();
               // return redirect('/')
               //     ->with('error', 'Error requesting access token')
               //     ->with('errorDetail', $e->getMessage());
            }
        }
		//header('Location: /msgraph/Auth/sign');
		//header("Location: /");
        //return redirect('/')
       //     ->with('error', $request->query('error'))
       //     ->with('errorDetail', $request->query('error_description'));
    }
	
    public function signout(){
        $tokenCache = new TokenCache();
        $tokenCache->clearTokens();
        //header('Location: /msgraph/Auth/sign');
		$this->render("index");
    }

    public function createClient($baseUrl,$apiVersion,$proxyPort = null){
        $tokenCache = new TokenCache();
        $token = $tokenCache->getAccessToken();
        if(empty($token)){
            return false;
        }
        $headers = [
            'Host' => $baseUrl.$apiVersion,
            'Content-Type' => 'application/json',
            'SdkVersion' => 'Graph-php-' . GraphConstants::SDK_VERSION,
            'Authorization' => 'Bearer ' . $token
        ];
        $clientSettings = [
            'base_uri' => $baseUrl,
            'headers' => $headers,
            'http_errors' => false
        ];
        if ($proxyPort !== null) {
            $clientSettings['verify'] = false;
            $clientSettings['proxy'] = $proxyPort;
        }
        $client = new \GuzzleHttp\Client($clientSettings);        
        return $client;
    }
	
	public function userForm() {
		
		$tokenCache = new TokenCache();
		$accessToken = $tokenCache->getAccessToken();

		// Create a Graph client
		$graph = new Graph();
		$graph->setAccessToken($accessToken);

		$allUsers = $graph->createRequest("GET", "/users?\$select=displayName,id&\$top=995")
					 	 ->setReturnType(Model\User::class)
						 ->execute();
		$result['managers'] = json_decode(json_encode($allUsers),true);
		$this->set($result);							 
		$this->render("index");
	}
	public function addUser(){	
		
		if(isset($_POST) && count($_POST)>1) {	 
			try {
			
					$extraInfo= '{
					   "userPrincipalName": "'.$_POST['mail'].'", 
					   "mailNickname":"'.$_POST['displayName'].'",
					   "companyName":"Multibank Group",
					   "passwordProfile": {                
									"password": "Mexgroup1"            
						 },
						"accountEnabled": true
						}';
					$extraArray = json_decode($extraInfo,true);
					
					$manager = $_POST['manager'];
					unset($_POST['manager']);
					$postArray = $_POST;
					
					$result = array_merge($postArray,$extraArray);
					$userData = json_encode($result);
			
		
					$tokenCache = new TokenCache();
					$accessToken = $tokenCache->getAccessToken();
	
					// Create a Graph client
					$graph = new Graph();
					$graph->setAccessToken($accessToken);
		
					$newuser = $graph->createRequest("POST", "/users")
									 ->attachBody($userData)
									 ->execute();
					$setManager = $graph->createRequest("PUT", "/users/".$_POST['mail']."/manager/\$ref")
									  ->attachBody('{"@odata.id": "https://graph.microsoft.com/v1.0/users/'.$manager.'"}')
									  ->execute();
					$_SESSION['success'] = 'User Created successfully';
				
			} catch (GuzzleHttp\Exception\ClientException $e) {
					$errorMessage = json_decode((string) $e->getResponse()->getBody());
					$_SESSION['errors']= $errorMessage->error->message;				
						
			}
   	  }
	  else {

	  		$_SESSION['errors'] = 'Fill all the mendatory fields';
			$this->render("index");
			die();
	  }
	  header('Location: /msgraph/Auth/userForm');
		

	}
}




	/*	
		$newUser = new Model\User();
    	$newUser->setGivenName("test");
		$newUser->setSurname("surnamse");
		$newUser->setAccountEnabled(true);
		$newUser->setDisplayName("test");
		$newUser->setMailNickname("test");
		$newUser->setUserPrincipalName("testzz");
		$newUser->setPasswordProfile('Password@123');
		$newUser->setJobTitle("Jobtitle");
		$newUser->setDepartment("Jobtitle");
		$newUser->setCity("Jobtitle");
		$newUser->setCountry("Jobtitle");
		$newUser->setOfficeLocation("Jobtitle");
//		$newUser->setManager("9a3fd83b-b9f5-4052-8e38-2443803e1df1");




'{"accountEnabled": true,
												  "displayName": "testt",
												  "mailNickname": "testt",
												  "surname":"abc",
												  "userPrincipalName": "testt@multibankfx.com",
												  "passwordProfile" : {
														"forceChangePasswordNextSignIn": true,
														"password": "P@assword1"
													}
												 }')		  
												 
												 
												 */