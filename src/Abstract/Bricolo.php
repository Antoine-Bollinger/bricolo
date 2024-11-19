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
use \Abollinger\Bricolo\Data\Messages;
use \Abollinger\Bricolo\Data\Constants;

/**
 * Class Bootstrap
 *
 * This abstract class provides fundamental functionality for bootstrapping processes.
 *
 * @package Abollinger\Bricolo
 */
abstract class Bricolo
{
    /**
     * Retrieves the root path of the application.
     *
     * This method computes the root path of the application by determining the
     * directory of the current script and adjusting the path to account for the
     * project structure. It also checks if a 'vendor' directory exists. If '-v'
     * is passed, it will output the root path.
     *
     * @param array $params Optional parameters, including '-v' for verbose output.
     * 
     * @return string The root path of the application.
     */
    public static function getRootPath(
        $params = []
    ) {
        $params = Helpers::defaultParams([
            "-v" => null,
        ], $params);

        $thisRootPath = dirname(dirname(__DIR__));

        $vendorPath = dirname(dirname(dirname($thisRootPath))) . "/vendor";

        $path = is_dir($vendorPath) ? dirname($vendorPath) : $thisRootPath;

        if ($params["-v"] === "") echo $path;

        return $path;
    }

    /**
     * Load .env variables 
     * 
     * @param bool $noError Control if the function throw an Exception or a simple string message when error is detected
     * 
     * @return string Error message if error and @param $noError is true
     * @return Exception if error and @param $noError is false
     */
    protected function _loadEnv(
        $params = []
    ) {
        $params = Helpers::defaultParams([
            "noError" => false,
            "path" => null
        ], $params);
        try {
            $dotenv = \Dotenv\Dotenv::createImmutable($params["path"] ?? dirname(__DIR__, 5));
            $dotenv->load();
        } catch(\Exception $e) {
            if ($params["noError"]) {
                return sprintf(Messages::WARNING(), $e->getMessage()) . "\n";
            } else {
                throw new \Exception($e->getMessage());
            }
        }
    }

    /**
     * Check the existence of the specified database.
     *
     * @return bool Returns true if the database exists; otherwise, false.
     */
    protected function _checkDatabase(

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
     * Create the specified database.
     * 
     * @return bool Returns true on successful database creation and population; otherwise, false.
     */
    protected function _createDatabase(

    ) :bool {
        $tmp = new \PDO("mysql:host=".$_ENV["D_HOST"].";charset=utf8mb4",$_ENV["D_USER"],$_ENV["D_PWD"]);
        $create = $tmp->prepare("
            CREATE DATABASE IF NOT EXISTS " . $_ENV["D_NAME"] . "
        ");
        $create->execute();
        $create->closeCursor();
        $mp = null;
        return true;
    }

    /**
     * Populate the database with data.
     *
     * @param string $file File containing the Sql command for database creation. Default is populate.sql in Data/templates folder
     * 
     * @return bool Returns true on successful database creation and population; otherwise, false.
     */
    protected function _populateDatabase(
        $path = Constants::dumpSqlPath
    ) {
        if (is_dir($path)) {

        } elseif (is_file($path)) {
            $this->_executeQueryFromFile($path);
        }
    }

    private function _executeQueryFromFile(
        $file = null
    ) {
        if ($file === null) return false;
        $queries = file_get_contents($file);
        $tmp = new \PDO("mysql:host=".$_ENV["D_HOST"].";dbname=".$_ENV["D_NAME"].";charset=utf8mb4",$_ENV["D_USER"],$_ENV["D_PWD"]);
        $execute = $tmp->prepare($queries);
        $execute->execute();
        $execute->closeCursor();
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
    protected function _checkPort(
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
    protected function _loading(
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

    /**
     * Promp a question to the user and capture the response
     * 
     * @param array $params An array of parameters for the loading animation.
     * 
     * @return string The response of the user.
     */
    protected function _input(
        $params = []
    ) :string {
        $params = Helpers::defaultParams([
            "q" => "How are you today? ",
        ], $params);

        echo $params["q"];
        $response = trim(fgets(STDIN));
        return $response ?? "";
    }

    /**
     * Perform the npm install command.
     *
     * @return string Return the result of the command.
     */
    protected function _npmInstall(

    ) {
        try {
            passthru("npm install", $exitCode);
            if ($exitCode === 0) {
                return sprintf(Messages::SUCCESS(), "npm install completed successfully");
            } else {
                throw new \Exception("npm install failed. Exit code: $exitCode");
            }
        } catch(\Exception $e) {
            return sprintf(Messages::ERROR(), $e->getMessage());
        }
    }
}