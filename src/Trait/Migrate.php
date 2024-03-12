<?php 
namespace Abollinger\Bricolo\Trait;

trait Migrate 
{
    /**
     * Perform migration tasks for the application.
     */
    public static function migrate(

    ) :void {
        try {
            try {
                $path = dirname(__DIR__, 5);
                $dotenv = \Dotenv\Dotenv::createImmutable($path);
                $dotenv->load();
            } catch(\Exception $e) {
                throw new \Exception($e->getMessage());
            }
            $instance = new self();
            $result = $instance->loading([
                "phrase" => "🔍️ \e[32mChecking if database ".$_ENV["D_HOST"]." exists",
                "position" => 1,
                "function" => "checkDatabase"
            ]);
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
            // echo "\n\e[32mDo you want to run 'composer serve' now? (yes/no) \e[39m[\e[33mno\e[39m]:";
            // $handle = fopen("php://stdin", "r");
            // $line = fgets($handle);
            // if (trim($line) === "yes") {
            //     echo "\n\e[32mAlright, 🚀 running the server right now!\n\e[39m";
            //     self::serve(["d" => "public"]);
            // }
            // fclose($handle);
        } catch(\Exception $e) {
            echo "🚨 \e[33m" . $e->getMessage() . "\e[39m";
        }
    }
}