<?php

class QuoteException extends Exception { }

class Quote {
  private $_id;
  private $_time;
  private $_context;
  private $_episodeID;
  private $_translationID;

  public function __construct($id, $time, $context, $episodeID, $translationID) {
    $this->setId($id);
    $this->setTime($time);
    $this->setContext($context);
    $this->setEpisodeID($episodeID);
    $this->setTranslationID($translationID);
  }

  public function getId() {
    return $this->_id;
  }

  public function getTime() {
    return $this->_time;
  }

  public function getContext() {
    return $this->_context;
  }

  public function getEpisodeID() {
    return $this->_episodeID;
  }

  public function getTranslationID() {
    return $this->_translationID;
  }

//   Time Validation Function

  public function isValidTime($time, $format = 'H:i:s') {
    if($time === null || $time === "") {
      return true;
    }
    $timeObj = DateTime::createFromFormat($format, $time);
    return $timeObj && $timeObj->format($format) == $time;
  }

  // Check ID is a number and is not null as per database rules

  public function setId($id) {
    if (($id !== null) && (!is_numeric($id) || $this->_id !== null)) {
      throw new QuoteException("Error: Quote ID Issue");
    }
    $this->_id = $id;
  }

  // Check Time is in the correct format, using Time Validation Function as per database rules

  public function setTime($time) {
    if(($time != null) && !$this->isValidTime($time, 'H:i:s')) {
      throw new QuoteException("Error: Time Issue");
    }
    $this->_time = $time;
  }

  public function setContext($context) {
    if (strlen($context) <= 0 || strlen($context) >= 1000) {
      throw new TranslationException("Error: Context Issue");
    }
    $this->_context = $context;
  }

  public function setEpisodeID($episodeID) {
    if (($episodeID !== null) && (!is_numeric($episodeID) || $this->_episodeID !== null)) {
      throw new QuoteException("Error: Episode ID Issue");
    }
    $this->_episodeID = $episodeID;
  }

  public function setTranslationID($translationID) {
    if (($translationID !== null) && (!is_numeric($translationID) || $this->_translationID !== null)) {
      throw new QuoteException("Error: Translation ID Issue");
    }
    $this->_translationID = $translationID;
  }

  public function getQuoteAsArray() {
    $quote = array();
    $quote['QuoteID'] = $this->getId();
    $quote['Time'] = $this->getTime();
    $quote['Context'] = $this->getContext();
    $quote['EpisodeID'] = $this->getEpisodeID();
    $quote['TranslationID'] = $this->getTranslationID();
    return $quote;
  }

}

?>