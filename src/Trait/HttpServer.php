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
 * Trait HttpServer
 *
 * @package Abollinger\Bricolo
 */
trait HttpServer
{
    /** @var string|null The host name used by the server. */
    private static $host;

    /** @var int|null The port number used by the server. */
    private static $port;

    /** @var int|null A specific document root directory. */
    private static $directory;

    /**
     * Initializes the server and checks for an available port to serve content.
     *
     * @throws \Exception When an error occurs during the server initialization.
     */
    public static function serve(
        $params = []
    ) :void {
        try {
            $params = Helpers::defaultParams([
                "h" => Constants::host,
                "p" => Constants::port,
                "d" => Constants::directory
            ], $params);

            $instance = new self();
            
            self::$host ??= (string)$params["h"];
            self::$port ??= (int)$params["p"];
            self::$directory ??= (string)$params["d"];
            
            $portIsUsed = $instance->_checkPort(self::$host, self::$port);
    
            if ($portIsUsed) {
                $instance->_loading([
                    "spinner" => ['-', '\\', '|', '/'],
                    "phrase" => "🚨 \e[33mPort ". self::$port . " is in use. Let's try with " . self::$port + 1 . " ",
                ]);
                echo "\n";
                self::$port++;
                self::serve([
                    "h" => self::$host,
                    "p" => self::$port,
                    "d" => self::$directory,
                ]);
            } else {
                // $instance->setPort(spl_object_id($instance), self::$port);
                echo "✅ \e[32mPort " . self::$port . " is available.\e[39m";
                echo "\n";
                $instance->_loading([
                    "phrase" => "\e[32mStarting the server\e[39m"
                ]);
                echo "\r";
            }

            shell_exec("php -S " . self::$host . ":" . self::$port . sprintf(self::$directory ? " -t %s/" : "", self::$directory));
        } catch(\Exception $e) {
            echo sprintf(Messages::ERROR(),$e->getMessage());
        }
    }
}