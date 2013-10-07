<?php
/**
 * Author: Pavel Kostenko
 * Email: poul.kg@gmail.com
 * Date: 07.10.13
 * Time: 20:55
 */

class CSyslogRoute extends CLogRoute
{

    /**
     * Processes log messages and sends them to specific destination.
     * Derived child classes must implement this method.
     * @param array $logs list of messages. Each array element represents one message
     * with the following structure:
     * array(
     *   [0] => message (string)
     *   [1] => level (string)
     *   [2] => category (string)
     *   [3] => timestamp (float, obtained by microtime(true));
     */
    protected function processLogs($logs)
    {
        foreach ($logs as $log) {
            list($msg, $level, $cat, $time) = $log;
            syslog($this->getPriority($level), $msg);
        }
    }

    /**
     * Returns syslog function supported priority by given CLogger level
     * i.e. this method converts log levels used in CLogger to alternatives used in syslog()
     * @param string $level
     * @return int
     */
    public function getPriority($level)
    {
        switch ($level) {
            case CLogger::LEVEL_TRACE:
                $priority = LOG_DEBUG;
                break;
            case CLogger::LEVEL_WARNING:
                $priority = LOG_WARNING;
                break;
            case CLogger::LEVEL_ERROR:
                $priority = LOG_ERR;
                break;
            case CLogger::LEVEL_INFO:
                $priority = LOG_INFO;
                break;
            case CLogger::LEVEL_PROFILE:
                $priority = LOG_DEBUG;
                break;
            default:
                $priority = LOG_WARNING;
                break;
        }
        return $priority;
    }
}