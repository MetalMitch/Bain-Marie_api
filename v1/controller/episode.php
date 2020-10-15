<?php

require_once('db.php');
require_once('../model/Episode.php');
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

if(array_key_exists("episodeid", $_GET)) {
  $episodeid = $_GET['episodeid'];

  if($episodeid == '' || !is_numeric($episodeid)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Episode ID: Cannot be null and must be numeric");
    $response->send();
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
      $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where ID = :episodeid');
      $query->bindParam(':episodeid', $episodeid, PDO::PARAM_INT);
      $query->execute();

      $rowCount = $query->rowCount();
      $episodeArray = array();

      if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Episode ID Not Found");
        $response->send();
        exit;
      }
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
        $episodeArray[] = $episode->getEpisodeAsArray();
      }

      $returnData = array();
      $returnData['rows_returned'] = $rowCount;
      $returnData['episodes'] = $episodeArray;

      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->toCache(true);
      $response->setData($returnData);
      $response->send();
      exit;
    }
    catch(EpisodeException $exception) {
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
      $response->addMessage("Failed to Retrieve Episode");
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
// elseif(array_key_exists("complete", $_GET)) {
//   $complete = $_GET['complete'];

//   if($complete !== 'Y' && $complete !== 'N') {
//     $response = new Response();
//     $response->setHttpStatusCode(400);
//     $response->setSuccess(false);
//     $response->addMessage("Error: Complete must be Y or N");
//     $response->send();
//     exit();
//   }

//   if($_SERVER['REQUEST_METHOD'] === 'GET') {

//     try {
//       $query = $readDB->prepare('select id, title, description, DATE_FORMAT(date, "%d-%m-%Y") as "date", start_time, end_time, DATE_FORMAT(deadline, "%d-%m-%Y %H:%i") as "deadline", complete from tbl_episodes where complete = :complete');
//       $query->bindParam(':complete', $complete, PDO::PARAM_STR);
//       $query->execute();

//       $rowCount = $query->rowCount();
//       $episodeArray = array();

//       while($row = $query->fetch(PDO::FETCH_ASSOC)) {
//         $episode = new Episode($row['id'], $row['title'], $row['description'], $row['date'], $row['start_time'], $row['end_time'], $row['deadline'], $row['complete']);
//         $episodeArray[] = $episode->getEpisodeAsArray();
//       }

//       $returnData = array();
//       $returnData['rows_returned'] = $rowCount;
//       $returnData['episodes'] = $episodeArray;

//       $response = new Response();
//       $response->setHttpStatusCode(200);
//       $response->setSuccess(true);
//       $response->toCache(true);
//       $response->setData($returnData);
//       $response->send();
//       exit();
//     }
//     catch(EpisodeException $exception) {
//       $response = new Response();
//       $response->setHttpStatusCode(400);
//       $response->setSuccess(false);
//       $response->addMessage($exception->getMessage());
//       $response->send();
//       exit();
//     }
//     catch(PDOException $exception) {
//       $response = new Response();
//       $response->setHttpStatusCode(500);
//       $response->setSuccess(false);
//       $response->addMessage("Error: Failed to Get Episodes");
//       $response->send();
//       exit();
//     }
//   }
//   else
//   {
//     $response = new Response();
//     $response->setHttpStatusCode(405);
//     $response->setSuccess(false);
//     $response->addMessage("Request method not allowed");
//     $response->send();
//     exit;
//   }
// }

else {
  $response = new Response();
  $response->setHttpStatusCode(404);
  $response->setSuccess(false);
  $response->addMessage("Error: Invalid Endpoint");
  $response->send();
  exit();
 }

?>
