<?php

require_once('db.php');
require_once('../model/Quote.php');
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

if(array_key_exists("quoteid", $_GET)) {
  $quoteid = $_GET['quoteid'];

  if($quoteid == '' || !is_numeric($quoteid)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Quote ID: Cannot be null and must be numeric");
    $response->send();
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
      $query = $readDB->prepare('select ID, TIME_FORMAT(time, "%H:%i:%s") as "time", context, episodeID, translationID from tbl_quotes where ID = :quoteid');
      $query->bindParam(':quoteid', $quoteid, PDO::PARAM_INT);
      $query->execute();

      $rowCount = $query->rowCount();
      $quoteArray = array();

      if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Quote ID Not Found");
        $response->send();
        exit;
      }
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $quote = new Quote($row['ID'], $row['time'], $row['context'], $row['episodeID'], $row['translationID']);
        $quoteArray[] = $quote->getQuoteAsArray();
      }

      $returnData = array();
      $returnData['Quotes'] = $quoteArray;
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->setRowsReturned($rowCount);
      $response->toCache(true);
      $response->setData($returnData);
      $response->send();
      exit;
    }
    catch(QuoteException $exception) {
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
      $response->addMessage("Failed to Retrieve Quote(s)");
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

if(array_key_exists("episodeID", $_GET)) {
    $episodeID = $_GET['episodeID'];
  
    if($episodeID == '' || !is_numeric($episodeID)) {
      $response = new Response();
      $response->setHttpStatusCode(400);
      $response->setSuccess(false);
      $response->addMessage("Episode ID: Cannot be null and must be numeric");
      $response->send();
      exit;
    }
  
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
  
      try {
        $query = $readDB->prepare('select ID, TIME_FORMAT(time, "%H:%i:%s") as "time", context, episodeID, translationID from tbl_quotes where episodeID = :episodeID');
        $query->bindParam(':episodeID', $episodeID, PDO::PARAM_INT);
        $query->execute();
  
        $rowCount = $query->rowCount();
        $quoteArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("Episode ID Not Found");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $quote = new Quote($row['ID'], $row['time'], $row['context'], $row['episodeID'], $row['translationID']);
          $quoteArray[] = $quote->getQuoteAsArray();
        }
  
        $returnData = array();
        $returnData['Quotes'] = $quoteArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->setRowsReturned($rowCount);
        $response->toCache(true);
        $response->setData($returnData);
        $response->send();
        exit;
      }
      catch(QuoteException $exception) {
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
        $response->addMessage("Failed to Retrieve Quote(s)");
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

  if(array_key_exists("translationID", $_GET)) {
    $translationID = $_GET['translationID'];
  
    if($translationID == '' || !is_numeric($translationID)) {
      $response = new Response();
      $response->setHttpStatusCode(400);
      $response->setSuccess(false);
      $response->addMessage("Translation ID: Cannot be null and must be numeric");
      $response->send();
      exit;
    }
  
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
  
      try {
        $query = $readDB->prepare('select ID, TIME_FORMAT(time, "%H:%i:%s") as "time", context, episodeID, translationID from tbl_quotes where translationID = :translationID');
        $query->bindParam(':translationID', $translationID, PDO::PARAM_INT);
        $query->execute();
  
        $rowCount = $query->rowCount();
        $quoteArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("Translation ID Not Found");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $quote = new Quote($row['ID'], $row['time'], $row['context'], $row['episodeID'], $row['translationID']);
          $quoteArray[] = $quote->getQuoteAsArray();
        }
  
        $returnData = array();
        $returnData['Quotes'] = $quoteArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->setRowsReturned($rowCount);
        $response->toCache(true);
        $response->setData($returnData);
        $response->send();
        exit;
      }
      catch(QuoteException $exception) {
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
        $response->addMessage("Failed to Retrieve Quote(s)");
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
