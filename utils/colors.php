#!/usr/bin/env php
<?php 
/*
 * This file is part of the Abollinger\Bricolo package.
 *
 * (c) Antoine Bollinger <antoine.bollinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$vendorAutoloadPath = (file_exists(__DIR__ . "/vendor/autoload.php"))
    ? __DIR__ . "/../vendor/autoload.php"
    : __DIR__ . "/../../../../vendor/autoload.php";

/**
 * Applies color to a string using the specified color code from the Constants class.
 *
 * @param string $string The input string.
 * @param string $color  The color code to apply.
 *
 * @return string The formatted string with the applied color.
 */
function sprintc($string = "", $color = "white") {
    // Use the ApplyColor method from the Constants class
    return \Abollinger\Bricolo\Data\Constants::ApplyColor($string, $color);
}
