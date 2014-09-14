<?php
if(!defined("IN_SYSTEM"))
  die('Direct Access Denied!');

class Database {
  public function connect() {
    global $config;

    $db_name = $config['db']['name'];
    $db_host = $config['db']['host'];
    $db_user = $config['db']['user'];
    $db_pass = $config['db']['password'];
    $db_char = $config['db']['charset'];
    
    if(mysql_connect($db_host, $db_user, $db_pass)) {
      mysql_query("SET NAMES ".$db_char);
      
      if(mysql_select_db($db_name)) {
        return($this);
      } else {
        die('Error selecting database: '.mysql_error());
        return(false);
      }
    } else {
      die('Error connecting to database: '.mysql_error());
      return(false);
    }	

    register_shutdown_function("autoclean");   
    register_shutdown_function("mysql_close");
  }

  public function dbArray($rowSet) {
    return(mysql_fetch_array($rowSet));
  }

  public function dbRow($rowSet) {
    return(mysql_fetch_row($rowSet));
  }

  public function dbAssoci($rowSet) {
    return(mysql_fetch_assoc($rowSet));
  }

  public function dbFreeResult($rowSet) {
    return(mysql_free_result($rowSet));
  }

  public function dbAssoc($rowSet, $singleRow = false) {
    $resultArray = array();

    while($row = mysql_fetch_assoc($rowSet)) {
      array_push($resultArray, $row);
    }

    if($singleRow === true && $resultArray)
      return $resultArray[0];

    return($resultArray);
  }

  public function dbNumRows($rowSet) {
    return(mysql_num_rows($rowSet));
  }

  public function dbQuery($what, $table, $where="") {
    if($where) {
      $sql = "SELECT $what FROM $table WHERE $where";
    } else {
      $sql = "SELECT $what FROM $table";
    }

    $result = mysql_query($sql) or die('Error selecting from database: '.mysql_error());    

    return($result);
  }

  public function dbInsert($table, $set, $value) {
    $sql = "INSERT INTO $table ($set) VALUES ($value)";

    $result = mysql_query($sql) or die('Error inserting into database: '.mysql_error());
    return;
  }

  public function dbUpdate($table, $set, $where="") {
    if($where) {
      $sql = "UPDATE $table SET $set WHERE $where";
    } else {
      $sql = "UPDATE $table SET $set";
    }

    $result = mysql_query($sql) or die('Error updating to database: '.mysql_error());
    return;
  }

  public function dbDelete($table, $where="") {
    if($where) {
      $sql = "DELETE FROM $table WHERE $where";
    } else {
      $sql = "DELETE FROM $table";
    }
    
    $result = mysql_query($sql) or die('Error deleting from database: '.mysql_error());
    return;
  }

  public function dbCount($table, $suffix="") {
    $r = mysql_query("SELECT COUNT(*) FROM $table $suffix") or die('Error selecting from database: '.mysql_error());
    $a = mysql_fetch_row($r) or die('Error selecting from database: '.mysql_error());
    return($a[0]);
  }
}