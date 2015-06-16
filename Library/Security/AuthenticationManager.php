<?php

namespace Library\Security;

use Library\Interfaces\IUser;
use Library\Enums\SessionKeys;

/**
 * <p> Provides the methods to manage user authentication. </p>
 */
class AuthenticationManager {

  private $app;

  public function __construct($app) {
    $this->app = $app;
  }

  /**
   * <p> Authenticates a user from the given object. </p>
   * @param \Library\Interfaces\IUser $user <p>
   * User object holding all the values necessary to connect the user. </p> 
   */
  public function authenticate(IUser $user) {
    //set role
    $this->app->user->setAttribute(SessionKeys::UserRole, $user->getRole());
    //store user in session
    $this->app->user->setAttribute(SessionKeys::UserConnected, $user);
  }

  /**
   * <p> Deauthenticate a user from current session. 
   * Then the session is detroyed. </p>
   */
  public function deauthenticate() {
    $this->app->user->unsetAttribute(SessionKeys::UserConnected);
    session_destroy();
  }

}
