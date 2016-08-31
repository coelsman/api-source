<?php
session_start();
$google_client_id     = '759964198229-keqdo7rqveggiaj09vmb66cvt566klal.apps.googleusercontent.com';
$google_client_secret = 'l3zdFFJlhp-p1wrQrmTHMcdn';
$google_redirect_url  = 'http://localhost:159/api-source/gglogin.php';
$google_developer_key = 'AIzaSyARODEk2S0domuxITrOQaisGXnFFyFPFZ4';

require_once 'gg/Google_Client.php';
require_once 'gg/contrib/Google_Oauth2Service.php';

class GGLogin {
	protected $_client_id;
	protected $_client_secret;
	protected $_redirect_url;
	protected $_developer_key;
	protected $_gClient;
	protected $_gOauth;

	public function __construct () {

	}

	public function setClientId ($client_id) {
		$this->_client_id = $client_id;
		return $this;
	}

	public function setClientSecret ($client_secret) {
		$this->_client_secret = $client_secret;
		return $this;
	}

	public function setRedirectUri ($redirect_url) {
		$this->_redirect_url = $redirect_url;
		return $this;
	}

	public function setDeveloperKey ($developer_key) {
		$this->_developer_key = $developer_key;
		return $this;
	}

	public function start () {
		$this->_gClient = new Google_Client();
		$this->_gClient->setApplicationName('Test');
		$this->_gClient->setClientId($this->_client_id);
		$this->_gClient->setClientSecret($this->_client_secret);
		$this->_gClient->setRedirectUri($this->_redirect_url);
		$this->_gClient->setDeveloperKey($this->_developer_key);

		$this->_gOauth = new Google_Oauth2Service($this->_gClient);

		if (isset($_REQUEST['reset'])) {
			unset($_SESSION['token']);
			$this->_gClient->revokeToken();
			header('Location: ' . filter_var($this->_redirect_url, FILTER_SANITIZE_URL)); //redirect user back to page
		}

		if (isset($_GET['code'])) { 
			$this->_gClient->authenticate($_GET['code']);
			$_SESSION['token'] = $this->_gClient->getAccessToken();
			header('Location: ' . filter_var($this->_redirect_url, FILTER_SANITIZE_URL));
			return;
		}

		if (isset($_SESSION['token'])) { 
			$this->_gClient->setAccessToken($_SESSION['token']);
		}

		if ($this->_gClient->getAccessToken()) {
			$user              = $this->_gOauth->userinfo->get();
			/*$user_id           = $user['id'];
			$user_name         = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
			$email             = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			$profile_url       = filter_var($user['link'], FILTER_VALIDATE_URL);
			$profile_image_url = filter_var($user['picture'], FILTER_VALIDATE_URL);
			$personMarkup      = "$email<div><img src='$profile_image_url?sz=50'></div>";
			$_SESSION['token'] = $this->_gClient->getAccessToken();*/
		} else {
			$authUrl = $this->_gClient->createAuthUrl();
		}

		if (isset($authUrl)) {
			header("Location: ".$authUrl);
		} else {
			echo '<pre>'; 
			print_r($user);
			echo '</pre>';	
		}
	}
}

$gg = new GGLogin();
$gg->setClientId($google_client_id)
->setClientSecret($google_client_secret)
->setRedirectUri($google_redirect_url)
->setDeveloperKey($google_developer_key)
->start();


?>