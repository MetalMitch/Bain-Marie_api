<?php

class TranslationException extends Exception { }

class Translation {
  private $_id;
  private $_french;
  private $_frSub;
  private $_english;
  private $_enSub;
  private $_explanation;
  private $_expLink;
  private $_expSite;
  private $_expDate;

  public function __construct($id, $french, $frSub, $english, $enSub, $explanation, $expLink, $expSite, $expDate) {
    $this->setId($id);
    $this->setFrench($french);
    $this->setFrSub($frSub);
    $this->setEnglish($english);
    $this->setEnSub($enSub);
    $this->setExplanation($explanation);
    $this->setExpLink($expLink);
    $this->setExpSite($expSite);
    $this->setExpDate($expDate);
  }

  public function getId() {
    return $this->_id;
  }

  public function getFrench() {
    return $this->_french;
  }

  public function getFrSub() {
    return $this->_frSub;
  }

  public function getEnglish() {
    return $this->_english;
  }

  public function getEnSub() {
    return $this->_enSub;
  }

  public function getExplanation() {
    return $this->_explanation;
  }

  public function getExpLink() {
    return $this->_expLink;
  }

  public function getExpSite() {
    return $this->_expSite;
  }

  public function getExpDate() {
    return $this->_expDate;
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
      throw new TranslationException("Error: Translation ID Issue");
    }
    $this->_id = $id;
  }

  // Check French Quote is not longer than 150 characters as per database rules

  public function setFrench($french) {
    if (strlen($french) <= 0 || strlen($french) >= 150) {
      throw new TranslationException("Error: French Quote Issue");
    }
    $this->_french = $french;
  }

// Check FrSub Quote is not longer than 150 characters as per database rules

public function setFrSub($frSub) {
    if (strlen($frSub) != 0 && strlen($frSub) >= 150) {
        throw new TranslationException("Error: French Sub-Quote Issue");
    }
    $this->_frSub = $frSub;
    }

  // Check English Quote is not longer than 150 characters as per database rules

  public function setEnglish($english) {
    if (strlen($english) <= 0 || strlen($english) >= 150) {
      throw new TranslationException("Error: English Quote Issue");
    }
    $this->_english = $english;
  }

// Check EnSub Quote is not longer than 150 characters as per database rules

public function setEnSub($enSub) {
    if (strlen($enSub) != 0 && strlen($enSub) >= 150) {
        throw new TranslationException("Error: English Sub-Quote Issue");
    }
    $this->_enSub = $enSub;
}

// Check Explanation  is not longer than 1000 characters as per database rules

public function setExplanation($explanation) {
    if (strlen($explanation) != 0 && strlen($explanation) >= 1000) {
        throw new TranslationException("Error: Explanation Issue");
    }
    $this->_explanation = $explanation;
}

// Check ExpLink is not longer than 150 characters as per database rules

public function setExpLink($expLink) {
    if (strlen($expLink) != 0 && strlen($expLink) >= 150) {
        throw new TranslationException("Error: Explanation Link Issue");
    }
    $this->_expLink = $expLink;
}

// Check ExpLink is not longer than 150 characters as per database rules

public function setExpSite($expSite) {
    if (strlen($expSite) != 0 && strlen($expSite) >= 35) {
        throw new TranslationException("Error: Explanation Site Issue");
    }
    $this->_expSite = $expSite;
}

  // Check Air Date is in the correct date format, using Date Validation Function as per database rules

  public function setExpDate($expDate) {
    if(($expDate != null) && !$this->isValidDate($expDate, 'd-m-Y')) {
      throw new TranslationException("Error: Explanation Site Date Issue");
    }
    $this->_expDate = $expDate;
  }

  public function getTranslationAsArray() {
    $translation = array();
    $translation['TranslationID'] = $this->getId();
    $translation['French'] = $this->getFrench();
    $translation['FrSub'] = $this->getFrSub();
    $translation['English'] = $this->getEnglish();
    $translation['EnSub'] = $this->getEnSub();
    $translation['Explanation'] = $this->getExplanation();
    $translation['ExplanationLink'] = $this->getExpLink();
    $translation['ExplanationSite'] = $this->getExpSite();
    $translation['ExplanationDate'] = $this->getExpDate();
    return $translation;
  }

}

?>