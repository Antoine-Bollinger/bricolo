<?php
/*
 * This file is part of the Abollinger\Bricolo package.
 *
 * (c) Antoine Bollinger <antoine.bollinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Abollinger\Bricolo\Data;

/**
 * Class Messages
 *
 * Provides static methods for generating messages related to different scenarios.
 *
 * @package Abollinger\Bricolo
 */
final class Messages 
{
    /**
     * Message to display after successful installation.
     *
     * @return string Formatted message.
     */
    public static function AFTERINSTALL(

    ) :string {
        return implode("\n", [
            sprintc("🎉 Congrats! You've just created a new Partez project!", "green"),
            sprintc("Now just run `composer serve` and see the magic happen! 🚀", "green")
        ]);
    }

    /**
     * Print PARTEZ stylised
     *
     * @return string Formatted message.
     */
    public static function PARTEZ(

    ) :string {
        return implode("\n", [
            sprintc("                      __", "green"),
            sprintc("    ____  ____  _____/ /_____  _____", "green"),
            sprintc("   / __ \/ __ \/ ___/ ___/ _ \/__  /", "green"),
            sprintc("  / /_/ / /_/ / /  / /  /  __/  __", "green"),
            sprintc(" / .___/\__/|/_/  /_/   \___/____/", "green"),
            sprintc("/_/", "green"),
        ]);
    }

    /**
     * Print the .env content.
     *
     * @return string Formatted message.
     */
    public static function ENV(

    ) :string {
        try {
            $path = dirname(__DIR__, 5);
            $dotenv = \Dotenv\Dotenv::createImmutable($path);
            $dotenv->load();
            $env = $_ENV;
        } catch(\Exception $e) {
            $env = ["error" => $e->getMessage()];
        }
        return var_dump($env);
    }

    /**
     * Message for displaying an succes.
     *
     * @return string Formatted error message.
     */
    public static function SUCCESS(

    ) :string {
        return sprintc("✅ %s", "green");
    }

    /**
     * Message for displaying an yellow.
     *
     * @return string Formatted error message.
     */
    public static function WARNING(

    ) :string {
        return sprintc("🚧 %s", "yellow");
    }

    /**
     * Message for displaying an error.
     *
     * @return string Formatted error message.
     */
    public static function ERROR(

    ) :string {
        return sprintc("🚨 Error: %s", "red");
    }

    /**
     * Help message providing information about available commands and usage.
     *
     * @return string Help message.
     */
    public static function HELP(

    ) :string {
        $composer = file_get_contents(dirname(__DIR__, 2) . "/composer.json");
        $composerJson = json_decode($composer, true);
        return "\e[32mBricolo\e[39m version \e[33m" . ($composerJson["version"] ?? "1.0.0") . "\e[39m " .  gmdate("d-m-Y H:i:s") . "

\e[33mUsage:\e[39m
    command [arguments]

\e[33mAvailable commands:\e[39m
    \e[32mhelp\e[39m        Display this same message 👽️
    \e[32mmigrate\e[39m     Create a database name partez (if not exists) and add a table user with one first use. Config in .env.
    \e[32mserve\e[39m       Starts a build-in serve on localhost:1234.
    \e[32m\e[39m            💡 You can customize port (p=8000), host (h=0.0.0.0) and directory (d=public).
    \e[32mupdate\e[39m      Update this package using `composer update abollinger/bricolo`. 
";
    }

    /**
     * Welcome message for new users.
     *
     * @return string Formatted welcome message.
     */
    public static function WELCOME(
        $text = ""
    ) :string {
        return implode("\n", [
            sprintc("Welcome to Bricolo's world!", "green"),
            sprintc("Type `help` to open your eyes to all our possibilities!", "yellow"),
            sprintc($text, "white")
        ]);
    }
}
