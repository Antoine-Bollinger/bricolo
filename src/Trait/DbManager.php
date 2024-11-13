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

use \Abollinger\Bricolo\Data\Messages;

/**
 * Trait DbManager
 *
 * This trait contains methods that help user to manage database.
 *
 * @package Abollinger\Bricolo
 */
trait DbManager 
{
    /**
     * Perform migration tasks for the application.
     */
    public static function migrate(

    ) :void {
        try {
            $instance = new self();
            
            $instance->_loadEnv();

            $result = $instance->_loading([
                "phrase" => "🔍️ \e[32mChecking if database ".$_ENV["D_HOST"]." exists",
                "position" => 1,
                "function" => "_checkDatabase"
            ]);
            if (!$result) {
                $instance->_loading([
                    "spinner" => ['-', '\\', '|', '/'],
                    "phrase" => "🚨 \e[33mDatabase doesn't exist, let's go create it! ",
                    "position" => 1,
                    "function" => "_createDatabase"
                ]);
                echo "\n";
                echo "\n✅ \e[32mDatabase has been successfully created and populated.\n📌 Next step is to run 'composer serve' and see the magic happen!\n\e[39m";
            } else {
                echo "\n✅ \e[32mDatabase already exists.\n📌 Next step is to run 'composer serve' and see the magic happen!\n\e[39m";
            }
        } catch(\Exception $e) {
            echo sprintf(Messages::ERROR(), $e->getMessage());
        }
    }
}