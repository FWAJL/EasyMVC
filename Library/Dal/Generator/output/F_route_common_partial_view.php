<?php
/*** @author Jeremie Litzler* @copyright Copyright (c) 2015* @licence http://opensource.org/licenses/gpl-license.php GNU Public License* @link https://github.com/WebDevJL/* @since Version 1.0.0.2* @package F_route_common_partial_view*/
namespace Library\BO;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) { exit('No direct script access allowed'); }
class F_route_common_partial_view extends \Library\Core\Entity {  protected     $f_route_id,    $f_common_partial_view_id;
  /**    * Sets f_route_id.  */  public function setF_route_id($f_route_id) {      $this->f_route_id = $f_route_id;  }
  /**    * Sets f_common_partial_view_id.  */  public function setF_common_partial_view_id($f_common_partial_view_id) {      $this->f_common_partial_view_id = $f_common_partial_view_id;  }
  /**    * Gets f_route_id.  */  public function F_route_id() {    return $this->f_route_id;  }
  /**    * Gets f_common_partial_view_id.  */  public function F_common_partial_view_id() {    return $this->f_common_partial_view_id;  }
}