<?php
/**
 * User: hernan
 * Date: 05/07/2018
 * Time: 12:13
 */

namespace LTFramework\Commands;


use Illuminate\Console\Command;
use LTFramework\Services\SetupCompiler;

class Setup extends Command {

    protected $signature = "lt-setup:init";

    protected $description = "this command make first setup of cms";

    /**
     * @var \LTFramework\Services\SetupCompiler
     */
    protected $compiler;

    public function __construct(SetupCompiler $compiler)
    {
        $this->compiler = $compiler;

        parent::__construct();
    }

    public function handle() {

        $this->comment('Lortom setup init ...');

        sleep(2);

        $this->info('Now, try to answer to the follow questions in order to setup .ENV file');

        $connection = $this->ask("Write me your connection (mysql | sqlite | sqlsrv | pgsql)","mysql");
        $host = $this->ask("Write me your host","127.0.0.1");
        $port = $this->ask("Write me your port", "3306");
        $database = $this->ask("Write me your Database Name", "db_name");
        $DBusername = $this->ask("Write me your DB Username","db_username");
        $DBpassword = $this->ask("Write me your DB password", "db_password");

        if($database === 'db_name' || $DBusername == 'db_username' || $DBpassword == 'db_password')
        {
            $this->error("Please, re-run the command and insert the DATABASE INFO");
            return;
        }

        $this->comment("writing .ENV file");

        $this->compiler->setupEnvFile($connection,$host,$port,$database,$DBusername,$DBpassword);

        $this->info('creating configuration files! ...');

        //create in config folder : customAction.php and dbexporter.php
        $this->compiler->copyFiles();

        $this->line('created: /config/customAction.php and /config/dbexporter.php');

        //insert dbName, dbUser and dbPassword

        $this->info('installing base plugins ...');

        //install plugin: Dashboard, Settings, Plugin, Website
        $this->compiler->installPlugins();

        $this->line('installed: Dashboard, Settings, Plugin and Website');
        $this->comment('fired migration for plugin');

        $this->info('Well done! Setup is finished, now build something of amazing!');
    }
}