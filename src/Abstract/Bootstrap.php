<?php 
/*
 * This file is part of the Abollinger\Bricolo package.
 *
 * (c) Antoine Bollinger <antoine.bollinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * Check the existence of the specified database.
     *
     * @return bool Returns true if the database exists; otherwise, false.
     */
    protected function checkDatabase(

    ) :bool {
        $tmp = new \PDO("mysql:host=".$_ENV["D_HOST"].";charset=utf8mb4",$_ENV["D_USER"],$_ENV["D_PWD"]);
        $check = $tmp->prepare("
            SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?
        ");
        $check->execute([$_ENV["D_NAME"]]);
        $result = $check->fetch(\PDO::FETCH_ASSOC);
        $tmp = null;
        return ($result !== false);
    }
    
    /**
     * Create the specified database and populate it with data.
     *
     * @return bool Returns true on successful database creation and population; otherwise, false.
     */
    protected function createDatabase(

    ) :bool {
        $tmp = new \PDO("mysql:host=".$_ENV["D_HOST"].";charset=utf8mb4",$_ENV["D_USER"],$_ENV["D_PWD"]);
        $create = $tmp->prepare("
            CREATE DATABASE IF NOT EXISTS " . $_ENV["D_NAME"] . "
        ");
        $create->execute();
        $create->closeCursor();

        $file = __DIR__ . "/dump_sql.txt";
        $queries = file_get_contents($file);

        $userId = $_ENV["FIRST_USER_ID"];
        $password = password_hash($_ENV["FIRST_USER_PASSWORD"], PASSWORD_BCRYPT, ["cost" => $_ENV["SALT"]]);

        $tmp = new \PDO("mysql:host=".$_ENV["D_HOST"].";dbname=".$_ENV["D_NAME"].";charset=utf8mb4",$_ENV["D_USER"],$_ENV["D_PWD"]);
        $populate = $tmp->prepare($queries);
        $populate->execute(["userId" => $userId, "password" => $password]);
        $populate->closeCursor();
        $tmp = null;
        return true;
    }

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