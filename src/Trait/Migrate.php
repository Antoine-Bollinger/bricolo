<?php 
namespace Bricolo\Trait;

trait Migrate 
{
    /**
     * Perform migration tasks for the application.
     */
    public static function migrate(

    ) :void {
        try {
            try {
                $dotenv = \Dotenv\Dotenv::createImmutable("/../../../");
                $dotenv->load();
            } catch(\Exception $e) {
                error_log("🚨 \e[33m" . $e->getMessage() . " Please create a .env at the root of the project. See .env-example.\e[39m");
            }
            $instance = new self();
            echo "\n";
            $result = $instance->loading([
                "phrase" => "🔍️ \e[32mChecking if database ".$_ENV["DB_HOST"]." exists",
                "position" => 1,
                "function" => "checkDatabase"
            ]);
            echo "\n";
            if (!$result) {
                $instance->loading([
                    "spinner" => ['-', '\\', '|', '/'],
                    "phrase" => "🚨 \e[33mDatabase doesn't exist, let's go create it! ",
                    "position" => 1,
                    "function" => "createDatabase"
                ]);
                echo "\n";
                echo "\n✅ \e[32mDatabase has been successfully created and populated.\n📌 Next step is to run 'composer serve' and see the magic happen!\n\e[39m";
            } else {
                echo "\n✅ \e[32mDatabase already exists.\n📌 Next step is to run 'composer serve' and see the magic happen!\n\e[39m";
            }
            echo "\n\e[32mDo you want to run 'composer serve' now? (yes/no) \e[39m[\e[33mno\e[39m]:";
            $handle = fopen("php://stdin", "r");
            $line = fgets($handle);
            if (trim($line) === "yes") {
                echo "\n\e[32mAlright, 🚀 running the server right now!\n\e[39m";
                self::serve();
            }
            fclose($handle);
        } catch(\Exception $e) {
            echo "🚨 \e[33m" . $e->getMessage() . "\e[39m";
        }
    }
}