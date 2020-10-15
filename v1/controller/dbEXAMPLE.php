<?php

class DB {

  private static $writeDBConnection;
  private static $readDBConnection;

  public static function connectWriteDB() {
    if (self::$writeDBConnection === null) {
//  localhost connection to xampp (mac) connecting the database in phpMyAdmin, default credentials are 'root'/''
      self::$writeDBConnection = new PDO('mysql:host=localhost;dbname=ofah;charset=utf8', 'mitch', 'sadbuttrue');
//  to see errors when using try and catch
      self::$writeDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//  legacy support for databases without prepared statements DISABLED
      self::$writeDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    return self::$writeDBConnection;
  }

  public static function connectReadDB() {
    if (self::$readDBConnection === null) {
//  localhost connection to xampp (mac) connecting the database in phpMyAdmin, default credentials are 'root'/''
      self::$readDBConnection = new PDO('mysql:host=localhost;dbname=ofah;charset=utf8', 'mitch', 'sadbuttrue');
//  to see errors when using try and catch
      self::$readDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//  legacy support for databases without prepared statements DISABLED
      self::$readDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    return self::$readDBConnection;
  }
}
?>
