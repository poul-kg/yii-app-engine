<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class GUserIdentity extends CUserIdentity
{
    public $email;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        /* @var $user \google\appengine\api\users\User */
        require_once 'google/appengine/api/users/UserService.php';
        $user = \google\appengine\api\users\UserService::getCurrentUser();
		if(!$user) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode=self::ERROR_NONE;
            $this->username = $user->getNickname();
            $this->email = $user->getEmail();
        }
		return !$this->errorCode;
	}
}