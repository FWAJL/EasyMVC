<?php

namespace Library\Dal\Generator;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) {
  exit('No direct script access allowed');
}

/**
 * Generates the DAO Classes from a database schema.
 * 
 * @author Jeremie Litzler
 * @copyright Copyright (c) 2015
 * @licence http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link https://github.com/WebDevJL/
 * @since Version 1.0.0
 * @package GeneratorManager
 */
class GeneratorManager extends \Library\Core\ApplicationComponent {

  public function GenerateDaoClasses() {
    $tableList = $this->app()->dal()->getDalInstance()->GetListOfTablesInDatabase();
    if ($tableList > 0) {
      foreach ($tableList as $table) {
        $table_name = $table[0];
        $tableColumnNames = $this->app()->dal()->getDalInstance()->GetTableColumnNames($table[0]);
        $tableColumnMeta = $this->app()->dal()->getDalInstance()->GetTableColumnsMeta($table[0], $tableColumnNames);
        $dao = new DaoClassGenerator(
                array(
                    "className" => ucfirst($table_name), 
                    "dir" => __ROOT__ . "Library/Dal/Generator/output/",
                    "type" => 
                      (!preg_match("`".\Library\Enums\GenericAppKeys::PREFIX_FRAMEWORK_TABLE.".*$`", $table_name) ? 
                      \Library\Enums\GenericAppKeys::APP_DB_TABLE : 
                      \Library\Enums\GenericAppKeys::FRAMEWORK_DB_TABLE)
                    )
                );
        $dao->BuildClassHeader($table_name);
        $dao->BuildClassBody($tableColumnMeta);
        $dao->ClassEnd();
      }
    } else {
      throw new \Exception("No tables in database!", 0, NULL);
    }
  }
}
