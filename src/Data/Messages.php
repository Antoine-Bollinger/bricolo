<?php

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

    ): string {
        return implode("\n", [
            sprintc("🎉 Congrats! You've just created a new Partez project!", "green"),
            sprintc("Now just run `composer serve` and see the magic happen! 🚀", "green")
        ]);
    }

    /**
     * Message for displaying an error.
     *
     * @return string Formatted error message.
     */
    public static function ERROR(

    ): string {
        return sprintc("🚨 Error: %s", "yellow");
    }

    /**
     * Help message providing information about available commands and usage.
     *
     * @return string Help message.
     */
    public static function HELP(

    ): string {
        return "\e[32mBricolo\e[39m version \e[33m1.0.0\e[39m " .  gmdate("d-m-Y H:i:s") . "

\e[33mUsage:\e[39m
    command [arguments]

\e[33mAvailable commands:\e[39m
    \e[32mhelp\e[39m        Display this same message 👽️
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
    ): string {
        return implode("\n", [
            sprintc("Welcome to Bricolo's world!", "green"),
            sprintc("Type `help` to open your eyes to all our possibilities!", "yellow"),
            sprintc($text, "white")
        ]);
    }
}
