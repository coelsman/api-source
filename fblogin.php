<?php
require_once 'fb/facebook.php';

class FBLogin {
	protected $_appId;
	protected $_appSecret;
	protected $_redirectUrl;
	protected $_fbPermissions;
	protected $_fb;

	public function __construct ($config = array()) {
		$this->_appId = '1659332157728141';
		$this->_appSecret = '1b0a6c3240d330b06282ce080e0d908d';
		$this->_fbPermissions = 'email';
		$this->_redirectUrl = @$config['redirectUrl'];
		$this->_fb = new Facebook(array(
			'appId'  => $this->_appId,
			'secret' => $this->_appSecret,
		));
	}

	public function isAvailableUser () {
		return $this->_fb->getUser();
	}

	public function getLoginUrl () {
		return $this->_fb->getLoginUrl(array(
			'redirect_uri' => $this->_redirectUrl,
			'scope' => $this->_fbPermissions
		));
	}

	public function getUserInfor () {
		return $this->_fb->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
	}
}

$base_url = 'http://localhost:159/api-source/';
$fb = new FBLogin(array('redirectUrl' => $base_url.'fblogin.php'));
// echo $fb->getLoginUrl();
// echo json_encode($fb->isAvailableUser());
// echo json_encode($fb->getUserInfor());