<?php

require_once('db.php');
require_once('../model/Translation.php');
require_once('../model/Response.php');

try {
  $writeDB = DB::connectWriteDB();
  $readDB = DB::connectReadDB();
}
catch(PDOException $exception) {
  error_log("Data Connection Error - ".$exception, 0);
  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage("Database Connection Failed");
  $response->send();
  exit;
}

if(array_key_exists("translationid", $_GET)) {
  $translationid = $_GET['translationid'];

  if($translationid == '' || !is_numeric($translationid)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Translation ID: Cannot be null and must be numeric");
    $response->send();
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
      $query = $readDB->prepare('select ID, french, frSub, english, enSub, explanation, expLink, expSite, DATE_FORMAT(expDate, "%d-%m-%Y") as "expDate" from tbl_translations where ID = :translationid');
      $query->bindParam(':translationid', $translationid, PDO::PARAM_INT);
      $query->execute();

      $rowCount = $query->rowCount();
      $translationArray = array();

      if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Translation ID Not Found");
        $response->send();
        exit;
      }
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $translation = new Translation($row['ID'], $row['french'], $row['frSub'], $row['english'], $row['enSub'], $row['explanation'], $row['expLink'], $row['expSite'], $row['expDate']);
        $translationArray[] = $translation->getTranslationAsArray();
      }

      $returnData = array();
      $returnData['translations'] = $translationArray;
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->setRowsReturned($rowCount);
      $response->toCache(true);
      $response->setData($returnData);
      $response->send();
      exit;
    }
    catch(TranslationException $exception) {
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage($exception->getMessage());
      $response->send();
      exit;
    }
    catch(PDOException $exception) {

      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage("Failed to Retrieve Translation(s)");
      $response->send();
      exit;
    }
  }
else {
  $response = new Response();
  $response->setHttpStatusCode(405);
  $response->setSuccess(false);
  $response->addMessage("Request method not allowed");
  $response->send();
  exit;
  }
}

elseif (array_key_exists("Year", $_GET) || array_key_exists("year", $_GET)) {
    $year = $_GET['Year'];
    if ($year === null) {
      $year = $_GET['year'];
    }

    if($year == '' || !is_numeric($year)) {
      $response = new Response();
      $response->setHttpStatusCode(400);
      $response->setSuccess(false);
      $response->addMessage("Year: Cannot be null and must be numeric");
      $response->send();
      exit;
    }
  
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
  
      try {
        $query = $readDB->prepare('select ID, french, frSub, english, enSub, explanation, expLink, expSite, DATE_FORMAT(expDate, "%d-%m-%Y") as "expDate" from tbl_translations where YEAR(`expDate`) = :year');
        $query->bindParam(':year', $year, PDO::PARAM_INT);
        $query->execute();
  
        $rowCount = $query->rowCount();
        $translationArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("Translations from Year Not Found");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $translation = new Translation($row['ID'], $row['french'], $row['frSub'], $row['english'], $row['enSub'], $row['explanation'], $row['expLink'], $row['expSite'], $row['expDate']);
            $translationArray[] = $translation->getTranslationAsArray();
        }
  
        $returnData = array();
        $returnData['translations'] = $translationArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->setRowsReturned($rowCount);
        $response->toCache(true);
        $response->setData($returnData);
        $response->send();
        exit;
      }
      catch(TranslationException $exception) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($exception->getMessage());
        $response->send();
        exit;
      }
      catch(PDOException $exception) {
  
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Failed to Retrieve Translation(s)");
        $response->send();
        exit;
      }
    }
  else {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit;
    }
  }

  elseif (array_key_exists("!Year", $_GET) || array_key_exists("!year", $_GET)) {
    $year = $_GET['!Year'];
    if ($year === null) {
      $year = $_GET['!year'];
    }

    if($year == '' || !is_numeric($year)) {
      $response = new Response();
      $response->setHttpStatusCode(400);
      $response->setSuccess(false);
      $response->addMessage("Year: Cannot be null and must be numeric");
      $response->send();
      exit;
    }
  
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
  
      try {
        $query = $readDB->prepare('select ID, french, frSub, english, enSub, explanation, expLink, expSite, DATE_FORMAT(expDate, "%d-%m-%Y") as "expDate" from tbl_translations where YEAR(`expDate`) != :year');
        $query->bindParam(':year', $year, PDO::PARAM_INT);
        $query->execute();
  
        $rowCount = $query->rowCount();
        $translationArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("Translations from Year Not Found");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $translation = new Translation($row['ID'], $row['french'], $row['frSub'], $row['english'], $row['enSub'], $row['explanation'], $row['expLink'], $row['expSite'], $row['expDate']);
            $translationArray[] = $translation->getTranslationAsArray();
        }
  
        $returnData = array();
        $returnData['translations'] = $translationArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->setRowsReturned($rowCount);
        $response->toCache(true);
        $response->setData($returnData);
        $response->send();
        exit;
      }
      catch(TranslationException $exception) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($exception->getMessage());
        $response->send();
        exit;
      }
      catch(PDOException $exception) {
  
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Failed to Retrieve Translation(s)");
        $response->send();
        exit;
      }
    }
  else {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit;
    }
  }

  else {
  $response = new Response();
  $response->setHttpStatusCode(404);
  $response->setSuccess(false);
  $response->addMessage("Error: Invalid Endpoint");
  $response->send();
  exit();
 }

?>
