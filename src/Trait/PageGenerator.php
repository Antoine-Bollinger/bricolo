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

use \Abollinger\Bricolo\Data\Constants;
use \Abollinger\Bricolo\Data\Messages;
use \Abollinger\Helpers;

/**
 * Trait PageGenerator
 *
 * @package Abollinger\Bricolo
 */
trait PageGenerator 
{
    /**
     * Creates a new page with a controller and a view.
     *
     * This method accepts parameters for the page name and route. It checks that
     * both values are provided, and if not, throws an exception. The method then
     * generates the file contents for a controller and view, replacing placeholders
     * for the name and route, and saves the generated files with the appropriate
     * names and extensions in their respective directories.
     *
     * @param array $params The parameters for the page, including 'name' and 'route'.
     * 
     * @throws \Exception If the 'name' or 'route' are not provided in the parameters.
     */
    public static function createPage(
        $params = []
    ) {        
        try {
            $params = Helpers::defaultParams([
                "name" => null,
                "route" => null
            ], $params);

            if (!$params["name"]) {
                throw new \Exception("Please provide a name for the page.");
            }            
            if (!$params["route"]) {
                throw new \Exception("Please provide a route for the page.");
            }

            $formattedName = ucfirst(strtolower($params["name"]));
    
            $contents = [
                "Controller" => [
                    "path" => self::getControllersPath(),
                    "extension" => "php",
                    "content" => file_get_contents(Constants::controllerFile)
                ],
                "View" => [
                    "path" => self::getViewsPath(),
                    "extension" => "twig",
                    "content" => file_get_contents(Constants::viewFile)
                ]
            ];
    
            foreach ($contents as $key => $value) {
                $fileContent = str_replace(
                    ["{{ route }}", "{{ name }}"],
                    [$params["route"], $formattedName],
                    $value["content"]
                );

                $fileName = $value["path"] . "/" . $formattedName . $key . "." . $value["extension"];
                echo sprintf(Messages::SUCCES(), $key . " has been created at " . $fileName . ".\n");
                file_put_contents($fileName, $fileContent);
            }
        } catch(\Exception $e) {
            echo sprintf(Messages::ERROR(), $e->getMessage());
        }
    }

    /**
     * Retrieves the path to the controllers directory.
     *
     * This method constructs the path to the controllers directory by combining
     * the root path and the value of the 'APP_CONTROLLERS' environment variable.
     * If the '-v' parameter is passed, it will echo the controllers path.
     *
     * @param array $params Optional parameters, including '-v' for verbose output.
     * 
     * @return string The path to the controllers directory.
     */
    public static function getControllersPath(
        $params = []
    ) {
        $params = Helpers::defaultParams([
            "-v" => null,
        ], $params);

        $instance = new self();

        $instance->_loadEnv(true);

        $controllersPath = self::getRootPath() . ($_ENV["APP_CONTROLLERS"] ?? "");

        if ($params["-v"] === "") echo $controllersPath;

        return $controllersPath;
    }
    
    /**
     * Retrieves the path to the views directory.
     *
     * Similar to `getControllersPath()`, this method constructs the path to the views
     * directory using the root path and the 'APP_VIEWS' environment variable. It
     * also supports the '-v' parameter for verbose output.
     *
     * @param array $params Optional parameters, including '-v' for verbose output.
     * 
     * @return string The path to the views directory.
     */
    public static function getViewsPath(
        $params = []
    ) {
        $params = Helpers::defaultParams([
            "-v" => null,
        ], $params);

        $instance = new self();

        $instance->_loadEnv(true);

        $viewsPath = self::getRootPath() . ($_ENV["APP_VIEWS"] ?? "");

        if ($params["-v"] === "") echo $viewsPath;

        return $viewsPath;
    }

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

        $instance = new self();

        $instance->_loadEnv(true);

        $thisRootPath = dirname(dirname(__DIR__));

        $vendorPath = dirname(dirname(dirname($thisRootPath))) . "/vendor";

        $path = is_dir($vendorPath) ? dirname($vendorPath) : $thisRootPath;

        if ($params["-v"] === "") echo $path;

        return $path;
    }
}
