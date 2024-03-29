<?php

  class Response {
    private $_success;
    private $_httpStatusCode;
    private $_rowsReturned;
    private $_messages = array();
    private $_data;
    private $_toCache = false;
    private $_responseData = array();

  public function setSuccess($success) {
    $this->_success = $success;
  }
  
  public function setHttpStatusCode($httpStatusCode) {
    $this->_httpStatusCode = $httpStatusCode;
  }

  public function setRowsReturned($rowsReturned) {
    $this->_rowsReturned = $rowsReturned;
  }

  public function addMessage($message) {
    $this->_messages[] = $message;
  }

  public function setData($data) {
    $this->_data = $data;
  }

  public function toCache($toCache) {
    $this->_toCache = $toCache;
  }

  public function send() {
    header('Content-type: application/json;charset=utf-8');

    if ($this->_toCache === true) {
      header('Cache-control: max-age=60');
    }
    else {
      header('Cache-control: no-cache, no-store');
    }

    if (($this->_success !== false && $this->_success !== true)  || !is_numeric($this->_httpStatusCode)) {
      $this->_responseData['Success'] = false;
      http_response_code(500);
      $this->addMessage("500 Internal Server Error");
      $this->_responseData['Message(s)'] = $this->_messages;
    }
    else {
      http_response_code($this->_httpStatusCode);
      $this->_responseData['RowsReturned'] = $this->_rowsReturned;
      if (!empty($this->_messages)) {
        $this->_responseData['Message(s)'] = $this->_messages;
      }
      $this->_responseData['DataReturned'] = $this->_data;
    }

    echo json_encode($this->_responseData);
  }
}
?>
