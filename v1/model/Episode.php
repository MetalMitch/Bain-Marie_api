<?php

class EpisodeException extends Exception { }

class Episode {
  private $_id;
  private $_episodeName;
  private $_seriesNum;
  private $_episodeNum;
  private $_airDate;
  private $_overview;
  private $_tmdbID;

  public function __construct($id, $episodeName, $seriesNum, $episodeNum, $airDate, $overview, $tmdbID) {
    $this->setId($id);
    $this->setEpisodeName($episodeName);
    $this->setSeriesNum($seriesNum);
    $this->setEpisodeNum($episodeNum);
    $this->setAirDate($airDate);
    $this->setOverview($overview);
    $this->setTmdbID($tmdbID);
  }

  public function getId() {
    return $this->_id;
  }

  public function getEpisodeName() {
    return $this->_episodeName;
  }

  public function getSeriesNum() {
    return $this->_seriesNum;
  }

  public function getEpisodeNum() {
    return $this->_episodeNum;
  }

  public function getAirDate() {
    return $this->_airDate;
  }

  public function getOverview() {
    return $this->_overview;
  }

  public function getTmdbID() {
    return $this->_tmdbID;
  }

//   Date Validation Function

  public function isValidDate($date, $format = 'Y-m-d') {
    if($date === null || $date === "") {
      return true;
    }
    $dateObj = DateTime::createFromFormat($format, $date);
    return $dateObj && $dateObj->format($format) == $date;
  }

  // Check ID is a number and is not null as per database rules

  public function setId($id) {
    if (($id !== null) && (!is_numeric($id) || $this->_id !== null)) {
      throw new EpisodeException("Error: Episode ID Issue");
    }
    $this->_id = $id;
  }

  // Check Episode Name is not longer than 33 characters as per database rules

  public function setEpisodeName($episodeName) {
    if (strlen($episodeName) <= 0 || strlen($episodeName) >= 33) {
      throw new EpisodeException("Error: Episode Name Issue");
    }
    $this->_episodeName = $episodeName;
  }

  public function setSeriesNum($seriesNum) {
    $this->_seriesNum = $seriesNum;
  }

  public function setEpisodeNum($episodeNum) {
    $this->_episodeNum = $episodeNum;
  }

  // Check Air Date is in the correct date format, using Date Validation Function as per database rules

  public function setAirDate($airDate) {
    if(($airDate != null) && !$this->isValidDate($airDate, 'd-m-Y')) {
      throw new EpisodeException("Error: Air Date Issue");
    }
    $this->_airDate = $airDate;
  }

    // Check overview is not longer than 539 characters as per database rules

  public function setOverview($overview) {
    if (strlen($overview) <= 0 || strlen($overview) >= 539) {
      throw new EpisodeException("Error: Overview Issue");
    }
    $this->_overview = $overview;
  }

  public function setTmdbID($tmdbID) {
    $this->_tmdbID = $tmdbID;
  }

  public function getEpisodeAsArray() {
    $episode = array();
    $episode['id'] = $this->getId();
    $episode['episodeName'] = $this->getEpisodeName();
    $episode['seriesNum'] = $this->getSeriesNum();
    $episode['episodeNum'] = $this->getEpisodeNum();
    $episode['airDate'] = $this->getAirDate();
    $episode['overview'] = $this->getOverview();
    $episode['tmdbID'] = $this->getTmdbID();
    return $episode;
  }

}

?>