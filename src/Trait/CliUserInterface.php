<?php 
/*
 * This file is part of the Abollinger\Bricolo package.
 *
 * (c) Antoine Bollinger <antoine.bollinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Abollinger\Bricolo\Trait;

use \Abollinger\Helpers;
use \Abollinger\Bricolo\Data\Constants;

/**
 * Trait CliUserInterface
 *
 * This trait contains methods that control the CLI user interface.
 *
 * @package Abollinger\Bricolo
 */
trait CliUserInterface 
{
    /**
     * Log a message based on parameters.
     *
     * @param array $params Parameters for logging.
     *
     * @throws \Exception When there is no log with the specified name.
     */
    public static function Log(
        $params = []
    ) :void {
        $params = Helpers::defaultParams([
            "m" => "welcome",
            "t" => ""
        ], $params);

        $className = "\\Abollinger\\Bricolo\\Data\\Messages";
        $method = strtoupper($params["m"]);
        $text = $params["t"];

        // Check if the constant exists in the Messages class
        if (defined($className . "::" . $method)) {
            echo constant($className . "::" . $method);
        } 
        // Check if the method exists in the Messages class
        elseif (method_exists($className, $method)) {
            echo call_user_func([$className, $method], $text);
        } 
        // If $method is a string, echo it directly
        elseif (is_string($method)) {
            echo $params["m"];
        } 
        // Throw an exception if there is no log with the specified name
        else {
            throw new \Exception("There is no log with the name `" . $params["m"] . "`.");
        }
    }

    public function createLauncher(

    ) {
        file_put_contents(self::getRootPath() . "/bricolo", file_get_contents(Constants::launcherFile));      
    }
}