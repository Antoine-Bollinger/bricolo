<?php 
/*
 * This file is part of the Abollinger\Bricolo package.
 *
 * (c) Antoine Bollinger <antoine.bollinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Abollinger\Bricolo;

use \Abollinger\Helpers;

/**
 * Class Bricolo
 *
 * Represents the main Bricolo class extending Abstract\Bootstrap and using Trait\Serve.
 *
 * @package Abollinger\Bricolo
 */
final class Bricolo extends Abstract\Bootstrap
{
    use Trait\Serve;
    use Trait\Migrate;

    /**
     * Display help information.
     */
    public static function Help() {
        self::Log(["m" => "help"]);
    }

    /**
     * Log a message based on parameters.
     *
     * @param array $params Parameters for logging.
     *
     * @throws \Exception When there is no log with the specified name.
     */
    public static function Log($params = []) {
        $params = Helpers::defaultParams([
            "m" => "welcome",
            "t" => ""
        ], $params);

        $className = "\\Abollinger\\Bricolo\\Data\\Messages";
        $method = strtoupper($params["m"]);
        $text = $params["t"];

        $messageName = "\\Abollinger\\Bricolo\\Data\\Messages::" . strtoupper($params["m"]);
        
        // Check if the constant exists in the Messages class
        if (defined($className . "::" . $method)) {
            echo constant($messageName);
        } 
        // Check if the method exists in the Messages class
        elseif (method_exists($className, $method)) {
            echo call_user_func([$className, $method], $text);
        } 
        // If $method is a string, echo it directly
        elseif (is_string($method)) {
            echo $method;
        } 
        // Throw an exception if there is no log with the specified name
        else {
            throw new \Exception("There is no log with the name `" . $params["m"] . "`.");
        }
    }

    /**
     * Update the Bricolo package using composer.
     */
    public static function Update() {
        shell_exec("composer update abollinger/bricolo");
    }
}
