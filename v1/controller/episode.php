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
      $returnData['Episodes'] = $episodeArray;
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->setRowsReturned($rowCount);
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
      $response->addMessage("Failed to Retrieve Episode(s)");
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
elseif(array_key_exists("seriesNum", $_GET)) {
  $seriesNum = $_GET['seriesNum'];

  if($seriesNum == '' || !is_numeric($seriesNum)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Series Number: Cannot be null and must be numeric");
    $response->send();
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(array_key_exists("episodeNum", $_GET)) {
      $episodeNum = $_GET['episodeNum'];

      try {
        $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where seriesNum = :seriesNum && episodeNum = :episodeNum');
        $query->bindParam(':seriesNum', $seriesNum, PDO::PARAM_INT);
        $query->bindParam(':episodeNum', $episodeNum, PDO::PARAM_INT);
        $query->execute();
  
        $rowCount = $query->rowCount();
        $episodeArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("Series Number Not Found");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
          $episodeArray[] = $episode->getEpisodeAsArray();
        }
  
        $returnData = array();
        $returnData['Episodes'] = $episodeArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->setRowsReturned($rowCount);
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
        $response->addMessage("Failed to Retrieve Episode(s)");
        $response->send();
        exit;
      }
    }

    try {
      $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where seriesNum = :seriesNum');
      $query->bindParam(':seriesNum', $seriesNum, PDO::PARAM_INT);
      $query->execute();

      $rowCount = $query->rowCount();
      $episodeArray = array();

      if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Series Number Not Found");
        $response->send();
        exit;
      }
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
        $episodeArray[] = $episode->getEpisodeAsArray();
      }

      $returnData = array();
      $returnData['Episodes'] = $episodeArray;
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->setRowsReturned($rowCount);
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
      $response->addMessage("Failed to Retrieve Episode(s)");
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
elseif(array_key_exists("episodeNum", $_GET)) {
  $episodeNum = $_GET['episodeNum'];

  if($episodeNum == '' || !is_numeric($episodeNum)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Episode Number: Cannot be null and must be numeric");
    $response->send();
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (array_key_exists("xmas", $_GET)) {
      $xmas = $_GET['xmas'];

      if(strtoupper($xmas) == 'N') {
        try {
          $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where episodeNum = :episodeNum && seriesNum != 0');
          $query->bindParam(':episodeNum', $episodeNum, PDO::PARAM_INT);
          $query->execute();
    
          $rowCount = $query->rowCount();
          $episodeArray = array();
    
          if($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("Episode Number Not Found");
            $response->send();
            exit;
          }
          while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
            $episodeArray[] = $episode->getEpisodeAsArray();
          }
    
          $returnData = array();
          $returnData['Episodes'] = $episodeArray;
          $response = new Response();
          $response->setHttpStatusCode(200);
          $response->setSuccess(true);
          $response->addMessage("Returned all episodes listed as number ".$episodeNum." in each season.");
          $response->addMessage("'Christmas Specials' have been omitted from results, to include them, pass 'xmas' parameter as Y or remove the parameter'");
          $response->addMessage("To search for episode number ".$episodeNum." in a specific series, pass the 'seriesNum' parameter with the number of the series you would like");
          $response->setRowsReturned($rowCount);
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
          $response->addMessage("Failed to Retrieve Episode(s)");
          $response->send();
          exit;
        }
    }
    elseif(strtoupper($xmas) == 'Y') {
      try {
        $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where episodeNum = :episodeNum');
        $query->bindParam(':episodeNum', $episodeNum, PDO::PARAM_INT);
        $query->execute();
  
        $rowCount = $query->rowCount();
        $episodeArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("Episode Number Not Found");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
          $episodeArray[] = $episode->getEpisodeAsArray();
        }
  
        $returnData = array();
        $returnData['Episodes'] = $episodeArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->addMessage("Returned all episodes listed as number ".$episodeNum." in each season.");
        $response->addMessage("Season 0 contains all episodes noted as 'Christmas Specials'");
        $response->addMessage("To omit 'Christmas Special' episodes from results, pass the parameter 'xmas' as N");
        $response->addMessage("To search for episode number ".$episodeNum." in a specific series, pass the 'seriesNum' parameter with the number of the series you would like");
        $response->setRowsReturned($rowCount);
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
        $response->addMessage("Failed to Retrieve Episode(s)");
        $response->send();
        exit;
      }
  }
    else {
      $response = new Response();
      $response->setHttpStatusCode(405);
      $response->setSuccess(false);
      $response->addMessage("Error: Invalid Parameter");
      $response->addMessage("xmas: Can only be 'Y' or 'N'");
      $response->send();
      exit;
      }
  }
    }

    try {
      $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where episodeNum = :episodeNum');
      $query->bindParam(':episodeNum', $episodeNum, PDO::PARAM_INT);
      $query->execute();

      $rowCount = $query->rowCount();
      $episodeArray = array();

      if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Episode Number Not Found");
        $response->send();
        exit;
      }
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
        $episodeArray[] = $episode->getEpisodeAsArray();
      }

      $returnData = array();
      $returnData['Episodes'] = $episodeArray;
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->addMessage("Returned all episodes listed as number ".$episodeNum." in each season.");
      $response->addMessage("Season 0 contains all episodes noted as 'Christmas Specials'");
      $response->addMessage("To omit 'Christmas Special' episodes from results, pass the parameter 'xmas' as N");
      $response->addMessage("To search for episode number ".$episodeNum." in a specific series, pass the 'seriesNum' parameter with the number of the series you would like");
      $response->setRowsReturned($rowCount);
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
      $response->addMessage("Failed to Retrieve Episode(s)");
      $response->send();
      exit;
    }
  }

  elseif (array_key_exists("tmdbID", $_GET)) {
    $tmdbID = $_GET['tmdbID'];

    if($tmdbID == '' || !is_numeric($tmdbID)) {
      $response = new Response();
      $response->setHttpStatusCode(400);
      $response->setSuccess(false);
      $response->addMessage("TMDB ID: Cannot be null and must be numeric");
      $response->send();
      exit;
    }
  
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
  
      try {
        $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where tmdbID = :tmdbID');
        $query->bindParam(':tmdbID', $tmdbID, PDO::PARAM_INT);
        $query->execute();
        $rowCount = $query->rowCount();
        $episodeArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("TMDB ID Not Found");
          $response->addMessage("Check whether this ID belongs to OFAH by using The Movie Database API");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
          $episodeArray[] = $episode->getEpisodeAsArray();
        }
  
        $returnData = array();
        $returnData['Episodes'] = $episodeArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->setRowsReturned($rowCount);
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
        $response->addMessage("Failed to Retrieve Episode(s)");
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
        $query = $readDB->prepare('select ID, episodeName, seriesNum, episodeNum, DATE_FORMAT(airDate, "%d-%m-%Y") as "airDate", overview, tmdbID from tbl_episodes where YEAR(`airDate`) = :year');
        $query->bindParam(':year', $year, PDO::PARAM_INT);
        $query->execute();
  
        $rowCount = $query->rowCount();
        $episodeArray = array();
  
        if($rowCount === 0) {
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("Episodes from Year Not Found");
          $response->send();
          exit;
        }
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $episode = new Episode($row['ID'], $row['episodeName'], $row['seriesNum'], $row['episodeNum'], $row['airDate'], $row['overview'], $row['tmdbID']);
          $episodeArray[] = $episode->getEpisodeAsArray();
        }
  
        $returnData = array();
        $returnData['Episodes'] = $episodeArray;
        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->setRowsReturned($rowCount);
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
        $response->addMessage("Failed to Retrieve Episode(s)");
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
