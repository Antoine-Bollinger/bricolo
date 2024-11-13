<?php 
/*
 * This file is part of the Abollinger\Bricolo package.
 *
 * (c) Antoine Bollinger <antoine.bollinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Abollinger\Bricolo;

use \Abollinger\Helpers;
use \Abollinger\Bricolo\Data\Messages;

/**
 * Class Bricolo
 *
 * Represents the main Bricolo class extending Abstract\Bricolo and using the traits as main methods.
 *
 * @package Abollinger\Bricolo
 */
final class Bricolo extends Abstract\Bricolo
{
    use Trait\CliUserInterface;
    use Trait\DbManager;
    use Trait\HttpServer;
    use Trait\PageGenerator;

    public function __construct(

    ) {
        try {
            $path = dirname(__DIR__, 4);
            $dotenv = \Dotenv\Dotenv::createImmutable($path);
            $dotenv->load();
        } catch(\Exception $e) {
            echo sprintf(Messages::ERROR(), $e->getMessage());
        }
    }

    /**
     * Display help information.
     */
    public static function help(

    ) {
        self::Log(["m" => "help"]);
    }

    /**
     * Update the Bricolo package using composer.
     */
    public static function update(
        
    ) {
        shell_exec("composer update abollinger/bricolo");
    }
}
