<?php

namespace Core;

use App\Config;
use Exception;

class Error
{
    /**
     * Error handler. Convert all errors to Exceptions by throwing and ErrorException.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     *
     * @return void
     * @throws \ErrorException
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception The exception
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        // Code is 404 (not found) or 500 (generic error)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }

        http_response_code($code);

        if (Config::SHOW_ERRORS) {
            echo "<h1>Fatal error.</h1>";
            echo "<p>Uncaught exception:" . get_class($exception) . "</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace: <pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in: '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        }
        else {
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "\nFatal error.";
            $message .= "\nUncaught exception: " . get_class($exception);
            $message .= "\nMessage: " . $exception->getMessage();
            $message .= "\nStack trace:\n" . $exception->getTraceAsString();
            $message .= "\nThrown in: '" . $exception->getFile() . "' on line " . $exception->getLine() . "\n\n";

            error_log($message);
            View::renderTemplate("$code.html");

        }
    }
}