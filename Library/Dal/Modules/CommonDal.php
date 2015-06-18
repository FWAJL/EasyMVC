<?php

/**
 * Provides methods to query the database for some generic queries that have
 * nothing to with the database tables.
 * 
 * @author Jeremie Litzler
 * @copyright Copyright (c) 2015
 * @licence http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link https://github.com/WebDevJL/EasyMVC
 * @since Version 1.0.0
 * @package CommonDal
 */

namespace Library\Dal\Modules;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) {
  exit('No direct script access allowed');
}

class CommonDal extends \Library\Dal\BaseManager {

  public function GetListOfTablesInDatabase() {
    $sth = $this->dao->prepare("SHOW TABLES;");
    return $this->ExecuteQuery($sth, array("type" => "TABLES"));
  }

  public function GetTableColumnsMeta($tableName, $columnNames) {
    $table_columns_meta = array();
    foreach ($columnNames as $columnName) {
      $sql = "SHOW COLUMNS FROM `$tableName` WHERE Field = '$columnName'";
      try {
        $sth = $this->dao->prepare($sql);
        $table_columns_meta[$columnName] = $this->ExecuteQuery($sth, array("type" => "COLUMNMETAS"));
      } catch (PDOException $exc) {
        echo $exc->getTraceAsString();
      }
    }
    return $table_columns_meta;
  }

  public function GetTableColumnNames($tableName) {
    $sth = $this->dao->prepare('DESCRIBE ' . $tableName . ';');
    return $this->ExecuteQuery($sth, array("type" => "COLUMNNAMES"));
  }

}
