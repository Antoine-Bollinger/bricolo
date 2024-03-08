<?php
namespace Abollinger\Bricolo\Abstract;

use \Abollinger\Helpers;

/**
 * Class Bootstrap
 *
 * This abstract class provides fundamental functionality for bootstrapping processes.
 *
 * @package Abollinger\Bricolo
 */
abstract class Bootstrap
{
    /**
     * Check if a port on a given host is in use.
     *
     * @param string $host The host to check.
     * @param int $port The port number to check.
     *
     * @return bool Returns true if the port is in use; otherwise, false.
     */
    protected function checkPort(
        $host = Constants::host,
        $port = Constants::port
    ) :bool {
        $connection = @fsockopen($host, $port, $errno, $errstr, 3);

        $isUsed = $connection !== false;

        if ($isUsed)
            fclose($connection);

        return $isUsed;
    }

    /**
     * Perform a loading animation or process.
     *
     * @param array $params An array of parameters for the loading animation.
     *
     * @return mixed Returns the result of the loading process, if any.
     */
    protected function loading(
        $params = []
    ) {
        $params = Helpers::defaultParams([
            "spinner" => [".", "..", "..."],
            "phrase" => "Loading",
            "position" => 1,
            "function" => ""
        ], $params);

        $loading = true;

        while ($loading) {
            foreach ($params["spinner"] as $char) {
                echo "\r" . ($params["position"] === 0 ? $char : "") . $params["phrase"] . ($params["position"] !== 0 ? $char : "") . "\e[39m";
                usleep(100000);
            }
            try {
                if (method_exists($this, $params["function"]))
                    $return = $this->{$params["function"]}();
            } catch(\Exception $e) {
                echo $e->getMessage();
            }
            $loading = false;
        }

        echo "\r" . $params["phrase"] . str_repeat(" ", Helpers::largestElementInArray($params["spinner"])) . "\e[39m";

        return $return ?? false;
    }
}