<?php

class Core_Logger
{
    /**
     * Static function to log an exception in the Logger file located in public folder
     */
    public static function log(Exception $e)
    {
        @file_put_contents(
            APP_PATH.'/public/'.APP_LOGFILE,
            '('.date('Ymd_his').' - '.$e->getFile().':'.$e->getLine().'): '. $e->getMessage().PHP_EOL,
            FILE_APPEND
        );

        // Add log in _errors table
        self::logInDatabase($e);
    }

    /**
     * Static function to log an exception specific from a job in his own file
     */
    public static function jobLog(Exception $e)
    {
        @file_put_contents(
            APP_PATH.'/public/'.APP_JOBLOGFILE,
            '('.date('Ymd_his').' - '.$e->getFile().':'.$e->getLine().'): '. $e->getMessage().PHP_EOL,
            FILE_APPEND
        );

        // Add log in _errors table
        self::logInDatabase($e);
    }

    /**
     * Static function to log an SQL exception in the SQL Logger file located in public folder
     */
    public static function SQLlog(Exception $e)
    {
        @file_put_contents(
            APP_PATH.'/public/'.APP_SQLLOGFILE,
            '('.date('Ymd_his').' - '.$e->getFile().':'.$e->getLine().'): '. $e->getMessage().PHP_EOL,
            FILE_APPEND
        );

        // Add log in _errors table
        self::logInDatabase($e);
    }

    /**
     * Static call to log in a file in public. This is to debugging purpose
     */
    public static function debug($message)
    {
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }

        try {
            @file_put_contents(
                APP_PATH.'/public/'.APP_DEBUGFILE,
                $message . PHP_EOL,
                FILE_APPEND
            );
        } catch (Exception $e) {
            /* FVS */echo __FILE__.': '.__LINE__.'<pre><br>';print_r($e);echo'</pre>';exit;
        }
    }

    /**
     * Add the log in _errors table
     */
    private function logInDatabase($e)
    {
        $model   = new Core_Model();
        $session = Core_Session::getInstance();

        $trace = $e->getTraceAsString();

        $trace = str_replace("'", "", $trace);
        $trace = str_replace('"', '', $trace);

        $msg = $e->getMessage();
        $msg = str_replace("'", "", $msg);
        $msg = str_replace('"', '', $msg);

        $company = ($session->company)
            ? "'{$session->company->getId()}'"
            : 'NULL';

        $sql = "INSERT INTO _errors(
                    file,
                    message,
                    script,
                    company_id)
                VALUES (
                    '{$e->getFile()}',
                    '{$msg}',
                    '{$trace}',
                    {$company}
                );";

        $model->execute($sql);
    }

    /**
     * Add the log in _errors table
     */
    public function dbLog($message)
    {
        $model   = new Core_Model();
        $session = Core_Session::getInstance();

        $msg = str_replace("'", "", $message);
        $msg = str_replace('"', '', $msg);

        $company = ($session->company)
            ? "'{$session->company->getId()}'"
            : 'NULL';

        $sql = "INSERT INTO _errors(
                    file,
                    message,
                    script,
                    company_id)
                VALUES (
                    'N/A',
                    '{$msg}',
                    'N/A',
                    {$company}
                );";

        $model->execute($sql);
    }
}