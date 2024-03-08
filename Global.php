<?php
$vendorAutoloadPath = (file_exists(__DIR__ . "/vendor/autoload.php"))
    ? __DIR__ . "/vendor/autoload.php"
    : __DIR__ . "/../../../vendor/autoload.php";

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
