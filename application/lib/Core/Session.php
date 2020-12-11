<?php

class Core_Session
{
    const S_ON = TRUE;
    const S_OFF = FALSE;

    public $sessionState = self::S_OFF; // session instance

    private static $instance;

    public $sid;

    /**
     * Public operation for singleton pattern to get the actual instance or build
     * a new one in case it doesnt exist
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        self::$instance->startSession();
        return self::$instance;
    }

    /**
     * If there is no session started it open a new one
     */
    public function startSession()
    {
        if ($this->sessionState == self::S_OFF){
            $this->sessionState = session_start();
            $this->sid = session_id();
        }

        return $this->sessionState;
    }

    /**
     * Magic method for setter to serialize, encode and set into session
     */
    public function __set($name, $value)
    {
        $value = serialize($value);
        $value = @base64_encode(APP_KEY . '}-!' . $value);
        $_SESSION[$name] = $value;
    }

    /**
     * Magic method getter to decode, unserialize and return the value, if it doesnt
     * exist then returns false
     */
    public function __get($name)
    {
        if (isset($_SESSION[$name])){

            $data = @explode('}-!', @base64_decode($_SESSION[$name]));
            $data = $data[1];
            return unserialize($data);
        }

        return false;
    }

    /**
     * Magic method to unset the value
     */
    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Destroy the session
     */
    public function destroy()
    {
        if ($this->sessionState == self::S_ON) {
            session_destroy();
            $this->sessionState = self::S_OFF;
            return true;
        }
        return false;
    }

    /**
     * Set a message in session to show in the next step
     */
    public function setMessage($msg_code, $msg_type = 'error', $name = '')
    {
        $this->has_msg = true;
        $this->msg_type = $msg_type;
        if (!isset($this->terms[$msg_code])) {
            throw new Exception("The message code ({$msg_code}) does not exist in session");
        }
        $this->msg = $this->terms[$msg_code] .' '. $name;
    }

    /**
     * Function to translate a code into the respective word
     */
    public function translate($message)
    {
        if (!isset($this->terms[$message])) {
            throw new Exception("The message code ({$message}) does not exist in session");
        }
        return $this->terms[$message];
    }
}