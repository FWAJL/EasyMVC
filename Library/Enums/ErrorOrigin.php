<?php

/**
 *
 * @package     The Loffy Framework
 * @author      Jeremie Litzler
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * ErrorOrigin Class
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

class ErrorOrigin {

  const Library = "error-origin-library";
  const Controller = "error-origin-controller";
  const View = "error-origin-view";
  const Helper = "error-origin-helper";
  const Dao = "error-origin-dao";
  const Dal = "error-origin-dal";

}
