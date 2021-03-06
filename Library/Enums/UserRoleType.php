<?php

/**
 *
 * @package     Easy MVC Framework
 * @author      Jeremie Litzler
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * UserRoleType Class
 *
 * @package       Library
 * @category    Enums
 * @author        Jeremie Litzler
 * @link		
 */

namespace Library\Enums;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) {
  exit('No direct script access allowed');
}

class UserRoleType {

  const Admin = "administrator_id";
  const Visitor = "";

}
