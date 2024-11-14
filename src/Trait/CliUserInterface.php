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
use \Abollinger\Bricolo\Data\Messages;

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

    /**
     * Create a bricolo file at the root of the project to allow user to use "php bricolo" instead of "php vendor/bin/bricolo" in CLI.
     *
     * @throws \Exception
     */
    public function createLauncher(

    ) {
        try {
            file_put_contents(self::getRootPath() . "/bricolo", file_get_contents(Constants::launcherFile));
            echo sprintf(Messages::SUCCESS(), "`bricolo` file successfully created at the root of the project.");
        } catch(\Exception $e) {
            echo sprintf(Messages::ERROR(), $e->getMessage());
        }
    }

    public function npmInstall(

    ) {
        $response = readline("Would you like to run npm install? [\e[33m[no]\e[0m, Yes]: ");
        $response = strtolower(trim($response));

        if ($response === 'yes' || $response === 'y') {
            $instance = new self();

            $npmInstall = $instance->_loading([
                "phrase" => "🚧 \e[32mRunning npm install",
                "position" => 1,
                "function" => "_npmInstall"
            ]);

            echo $npmInstall;
        } else {
            echo "Skipping npm install.\n";
        }
    }
}