<?php

/**
* @author Jeremie Litzler
* @copyright Copyright (c) 2015
* @licence http://opensource.org/licenses/gpl-license.php GNU Public License
* @link https://github.com/WebDevJL/
* @since Version 1.0.0
* @package DaoClassGenerator
*/

namespace Library\Dal\Generator;

class DaoClassGenerator {

  protected
          $className,
          $dir;
  private
          $fileName,
          $writer,
          $_CRLF = "\n\r",
          $_LF = "\r",
          $_TAB2 = "  ",
          $_TAB4 = "    ",
          $_TAB6 = "      ",
          $_TAB8 = "        ",
          $placeholders,
          $isFrameworkClass = true,
          $baseClass = "\Library\Core\Entity";

  public function __construct($params) {
    $this->dir = $params["dir"];
    $this->className = $params["className"];
    $this->fileName = $this->className . ".php";
    $this->placeholders = array(
        PhpDocPlaceholder::AUTHOR => "Jeremie Litzler",
        PhpDocPlaceholder::COPYRIGHT_YEAR => date("Y"),
        PhpDocPlaceholder::LICENCE => "http://opensource.org/licenses/gpl-license.php GNU Public License",
        PhpDocPlaceholder::LINK => "https://github.com/WebDevJL/",
        PhpDocPlaceholder::PACKAGE => $this->className,
        PhpDocPlaceholder::SUBPACKAGE => "",
        PhpDocPlaceholder::VERSION_NUMBER => __VERSION_NUMBER__,
        CodeSnippetPlaceholders::NAMESPACE_FRAMEWORK => "Library\BO",
        CodeSnippetPlaceholders::NAMESPACE_APP => "\Applications\"" . __APPNAME__ . "\Models\Dao",
        CodeSnippetPlaceholders::CLASS_NAME => $this->className
    );
    $this->isFrameworkClass = $params["type"] === \Library\Enums\GenericAppKeys::APP_DB_TABLE ? FALSE : TRUE;
  }

  public function OpenWriter() {
    $filePath = $this->dir . $this->fileName;
    echo $filePath . "<br />";

    $this->writer = fopen($filePath, 'w') or die("can't open file");
  }

  public function CloseWriter($params) {
    fclose($this->writer);
  }

  public function AddPhpOpenTag() {
    fwrite($this->writer, "<?php" . $this->_CRLF);
  }

  public function AddNameSpace() {
    $output = "";
    if ($this->isFrameworkClass) {
      $output = strtr(CodeSnippets::SNIPPET_NAMESPACE_FRAMEWORK, $this->placeholders);
    } else {
      $output = strtr(CodeSnippets::SNIPPET_NAMESPACE_APP, $this->placeholders);
    }
    fwrite($this->writer, $output);
  }

  public function AddFileDescription() {
    $output = PhpDocConstants::OPENING . $this->_LF .
            strtr(PhpDocConstants::AUTHOR, $this->placeholders) . $this->_LF .
            strtr(PhpDocConstants::COPYRIGHT, $this->placeholders) . $this->_LF .
            strtr(PhpDocConstants::LICENCE, $this->placeholders) . $this->_LF .
            strtr(PhpDocConstants::LINK, $this->placeholders) . $this->_LF .
            strtr(PhpDocConstants::SINCE, $this->placeholders) . $this->_LF .
            strtr(PhpDocConstants::PACKAGE, $this->placeholders) . $this->_LF .
            PhpDocConstants::CLOSING . $this->_CRLF;
    fwrite($this->writer, $output);
  }

  public function AddScriptNotAllowedLine() {
    fwrite($this->writer, $this->_LF . "if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) { exit('No direct script access allowed'); }");
  }

  public function ClassStart() {
    $output = $this->_CRLF . "class " . ucfirst($this->className) . " extends " . $this->baseClass . " {" . $this->_LF;
    fwrite($this->writer, $output);
  }

  public function ClassEnd() {
    fwrite($this->writer, $this->_LF . "}");
  }

  public function BuildClassHeader($table_name) {
    $this->OpenWriter();
    $this->AddPhpOpenTag();
    $this->AddFileDescription($table_name);
    $this->AddNameSpace();
    $this->AddScriptNotAllowedLine();
    $this->ClassStart();
  }

  public function BuildClassBody($table_col_metas) {
    //Build the properties
    $this->AddFields($table_col_metas);
    //Add setters
    $this->AddSetters($table_col_metas);
    //Add getters
    $this->AddGetters($table_col_metas);
  }

  private function AddFields($columns) {
    //Write the protected fields
    $output = $this->_TAB2 . "protected " . $this->_LF;
    $columnCount = 0;
    foreach ($columns as $columnName => $columnMeta) {
      if (count($columns) - 1 === $columnCount) {
        $output .= $this->_TAB4 . "$" . $columnMeta[0]["Field"] . ";" . $this->_CRLF;
      } else {
        $output .= $this->_TAB4 . "$" . $columnMeta[0]["Field"] . "," . $this->_LF;
      }
      $columnCount += 1;
    }
    fwrite($this->writer, $output);
  }

  private function AddSetters($columns) {
    $output = "";
    foreach ($columns as $columnName => $columnMeta) {
      $output .= $this->AddPropertyPhpDoc($columnMeta);
      $placeholders = array(
          CodeSnippetPlaceholders::PROPERTY_NAME_FIRST_CAP => ucfirst($columnMeta[0]["Field"]),
          CodeSnippetPlaceholders::PROPERTY_NAME => $columnMeta[0]["Field"]);
      $output .=
              $this->_TAB2 .
              strtr(CodeSnippets::SNIPPET_SET_PROPERTY_START, $placeholders) . $this->_LF;
      $output .=
              $this->_TAB6 .
              strtr(CodeSnippets::SNIPPET_SET_PROPERTY_ASSIGNMENT, $placeholders) .
              $this->_LF;
      $output .= $this->_TAB2 . CodeSnippets::SNIPPET_CLOSING_CURLY_BRACKET . $this->_CRLF;
    }
    fwrite($this->writer, $output);
  }

  private function AddGetters($columns) {
    $output = "";
    foreach ($columns as $columnName => $columnMeta) {
      $output .= $this->AddPropertyPhpDoc($columnMeta, FALSE);
      $placeholders = array(
          CodeSnippetPlaceholders::PROPERTY_NAME_FIRST_CAP => ucfirst($columnMeta[0]["Field"]),
          CodeSnippetPlaceholders::PROPERTY_NAME => $columnMeta[0]["Field"]);

      $output .= $this->_TAB2 .
              strtr(CodeSnippets::SNIPPET_GET_PROPERTY_START, $placeholders) .
              $this->_LF;
      $output .=
              $this->_TAB4 .
              strtr(CodeSnippets::SNIPPET_GET_PROPERTY_MIDDLE, $placeholders) .
              $this->_LF;
      $output .= $this->_TAB2 . CodeSnippets::SNIPPET_CLOSING_CURLY_BRACKET . $this->_CRLF;
    }
    fwrite($this->writer, $output);
  }

  private function AddPropertyPhpDoc($columnMeta, $isSetter = TRUE) {
    $output = 
            $this->_TAB2 . PhpDocConstants::OPENING . 
            $this->_TAB2 . $this->_LF;
    $placeholders = array(
        "{{set_dynamic_code}}" => strtr(PhpDocConstants::SET_PROPERTY_SUMMARY, array(PhpDocPlaceholder::SET_PROPERTY => $columnMeta[0]["Field"])),
        "{{get_dynamic_code}}" => strtr(PhpDocConstants::GET_PROPERTY_SUMMARY, array(PhpDocPlaceholder::GET_PROPERTY => $columnMeta[0]["Field"]))
    );
    if ($isSetter) {
      $output .= $this->_TAB2 . strtr("{{set_dynamic_code}}", $placeholders);
    } else {
      $output .= $this->_TAB2 . strtr("{{get_dynamic_code}}", $placeholders);
    }
    $output .= $this->_LF . $this->_TAB2 . PhpDocConstants::CLOSING . $this->_LF;
    return $output;
  }

}
