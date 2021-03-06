<?php

/**
 * Controllers to manage the different authentication methods (login, logout, etc.)
 * 
 * @author Jeremie Litzler
 * @copyright Copyright (c) 2015
 * @licence http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link https://github.com/WebDevJL/EasyMVC
 * @since Version 1.0.0
 * @package AuthenticateController
 */

namespace Applications\EasyMvc\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) {
  exit('No direct script access allowed');
}

class AuthenticateController extends \Library\Controllers\BaseController {

  /**
   * Method that loads the Login view. <p/>
   *
   * @param \Library\Core\HttpRequest $rq
   * The current request. 
   */
  public function LoadLoginView() {
    $this->Disconnect(FALSE);
  }

  /**
   * Authenticate a user from JS Client and return the result.
   *
   * @param \Library\Core\HttpRequest $rq
   * The current request. </p>
   * @return json
   * A JSON object with the result bool value and success/error message. 
   */
  public function Authenticate() {
    $result = $this->InitResponseWS();
    $userPost = \Library\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Library\BO\F_user());
    $userDatabase =
            $this
            ->app()
            ->dal()
            ->getDalInstance('Login')
            ->selectOne($userPost);
    $dbResult = -1;
    if (count($userDatabase) > 0) {
      $user = $userDatabase[0];
      if (!$user->F_user_password_is_hashed() && $this->app()->auth()->CheckPassword($userPost->F_user_password(), $user, TRUE)) {
        $user = $this->app()->auth()->HashUserPassword($user);
        $dbResult = $this->app()->dal()->getDalInstance()->edit($user, "f_user_id");
      } else {
        $user = $this->app()->auth()->CheckPassword($userPost->F_user_password(), $user);
        $dbResult = !$user ? -2 : 1;
      }
      //User is correct so log him in and set result to success
      if ($dbResult > 0) {
        $this->app()->auth()->authenticate($user);
      }
    }
    $this->SendResponseWS($result, array(
        "resx_file" => \Applications\EasyMvc\Resources\Enums\ResxFileNameKeys::Login,
        "resx_key" => $this->action(),
        "step" => $dbResult > 0 ? "success" : "error"
    ));
  }

  /**
   * Method that logout a user from the session and then redirect him to Login page.
   *
   * @param \Library\HttpRequest $rq
   */
  public function Disconnect($redirect = TRUE) {
    $this->app()->auth->deauthenticate();
    if ($redirect) {
      $this->Redirect("login");
    }
  }

  /**
   * Method that logout a user from the session and then redirect him to Login page.
   *
   * @param \Library\HttpRequest $rq
   */
  public function Create() {
    $protect = new \Library\Security\Encryption($this->app()->config());
    $data = array(
        "username" => $rq->getData("login"),
        "password" => $rq->getData("pwd"),
        "pm_name" => "Demo User"
    );
    //TODO: implement user creation
    $user = \Library\Helpers\CommonHelper::PrepareUserObject($data, new Library\BO\User());
    $user->setPassword($protect->HashValue($this->app->config->get("PaswordSalt"), $user->password()));

    $id =
            $this
            ->app()
            ->dal()
            ->getDalInstance('Login')
            ->add($user);
    
    $redirect = intval($id) > 0 ? TRUE : FALSE;

    if ($redirect) {
      $this->Redirect("login");
    }
  }
}
