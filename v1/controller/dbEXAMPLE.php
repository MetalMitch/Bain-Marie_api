<?php

class DB {

  private static $writeDBConnection;
  private static $readDBConnection;

  public static function connectWriteDB() {
    if (self::$writeDBConnection === null) {
      self::$writeDBConnection = new PDO('redacted');
//  to see errors when using try and catch
      self::$writeDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//  legacy support for databases without prepared statements DISABLED
      self::$writeDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    return self::$writeDBConnection;
  }

  public static function connectReadDB() {
    if (self::$readDBConnection === null) {
      self::$readDBConnection = new PDO('redacted');
//  to see errors when using try and catch
      self::$readDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//  legacy support for databases without prepared statements DISABLED
      self::$readDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    return self::$readDBConnection;
  }
}
?>
