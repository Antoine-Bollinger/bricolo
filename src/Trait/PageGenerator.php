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
                echo $fileName . "\n";
                file_put_contents($fileName, $fileContent);
            }
            
            echo sprintf(Messages::SUCCESS(), "Page created successfully.");
        } catch(\Exception $e) {
            echo sprintf(Messages::ERROR(), $e->getMessage());
        }
    }

    public static function getControllersPath(
        $params = []
    ) {
        $params = Helpers::defaultParams([
            "-v" => null,
        ], $params);

        $controllersPath = self::getRootPath() . (getenv('APP_CONTROLLERS') ?: "");

        if ($params["-v"] === "") echo $controllersPath;

        return $controllersPath;
    }
    
    public static function getViewsPath(
        $params = []
    ) {
        $params = Helpers::defaultParams([
            "-v" => null,
        ], $params);

        $viewsPath = self::getRootPath() . (getenv('APP_VIEWS') ?: "");

        if ($params["-v"] === "") echo $viewsPath;

        return $viewsPath;
    }

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
}
