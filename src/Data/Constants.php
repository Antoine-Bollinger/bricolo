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
 * Class Constants 
 * 
 * To manage various constants and color codes.
 *
 * @package Abollinger\Bricolo
 */
abstract class Constants
{
    /**
     * Host constant.
     */
    const host = "localhost";

    /**
     * Port constant.
     */
    const port = 1234;

    /**
     * Text file to store the port
     */
    const portFile = __DIR__ . "/port.txt";

    /**
     * Text file with dump sql for base migration
     */
    const dumpSqlFile = __DIR__ . "/dump_sql.txt";

    /**
     * Directory constant.
     */
    const directory = "";

    /**
     * Array of color codes.
     *
     * @var array
     */
    const colors = [
        'bold' => "\033[1m%s\033[0m",
        'dark' => "\033[2m%s\033[0m",
        'italic' => "\033[3m%s\033[0m",
        'underline' => "\033[4m%s\033[0m",
        'blink' => "\033[5m%s\033[0m",
        'reverse' => "\033[7m%s\033[0m",
        'concealed' => "\033[8m%s\033[0m",
        'black' => "\033[30m%s\033[0m",
        'red' => "\033[31m%s\033[0m",
        'green' => "\033[32m%s\033[0m",
        'yellow' => "\033[33m%s\033[0m",
        'blue' => "\033[34m%s\033[0m",
        'magenta' => "\033[35m%s\033[0m",
        'cyan' => "\033[36m%s\033[0m",
        'white' => "\033[37m%s\033[0m",
        'bg_black' => "\033[40m%s\033[0m",
        'bg_red' => "\033[41m%s\033[0m",
        'bg_green' => "\033[42m%s\033[0m",
        'bg_yellow' => "\033[43m%s\033[0m",
        'bg_blue' => "\033[44m%s\033[0m",
        'bg_magenta' => "\033[45m%s\033[0m",
        'bg_cyan' => "\033[46m%s\033[0m",
        'bg_white' => "\033[47m%s\033[0m",
    ];

    /**
     * Apply color to a string using the specified color code.
     *
     * @param string $string The input string.
     * @param string $color  The color code to apply.
     *
     * @return string The formatted string with the applied color.
     */
    public static function ApplyColor(
        $string = "",
        $color = "white"
    ): string {
        return sprintf(self::colors[$color], $string);
    }
}