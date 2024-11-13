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
                throw new \Exception("Please give a name to the page you want to create.");
            }            
            
            if (!$params["route"]) {
                throw new \Exception("Please give a route to the page you want to create.");
            }

            $formattedName = ucfirst(strtolower($params["name"]));
    
            $contents = [
                "Controller" => [
                    "extension" => "php",
                    "content" => file_get_contents(Constants::controllerFile)
                ],
                "View" => [
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
                $mainPath = getenv("APP_CONTROLLERS") ?? dirname(dirname(__DIR__));
                $fileName = $mainPath . "/" . $formattedName . $key . "." . $value["extension"];
                file_put_contents($fileName, $fileContent);
            }
        } catch(\Exception $e) {
            echo sprintf(Messages::ERROR(),$e->getMessage());
        }
    } 
}