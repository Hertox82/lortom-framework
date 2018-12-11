<?php
/**
 * User: hernan
 * Date: 05/07/2018
 * Time: 15:37
 */

namespace LTFramework\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Artisan;
use File;

class SetupCompiler {

    protected $plugins = [
        "Hardel@Dashboard" => "1.0.0",
        "Hardel@Settings" => "1.0.0",
        "Hardel@Plugin" => "1.0.0",
        "Hardel@Website" => "1.0.0",
        "Hardel@File" => "1.0.0"
    ];

    protected $node;

    protected $ltpm;

    protected $ENV = [];

    /**
     * @var DatabaseManager
     */
    protected $database;


    public function __construct(DatabaseManager $db)
    {
        $this->node = $_ENV['NODE_JS'];
        $this->ltpm = $_ENV['LTPM'];
        $this->database = $db;
    }

    /**
     * This method install Basic Plugin into the CMS
     */
    public function installPlugins(){

        $listPlugin = $this->sanitizePluginsArray();

        foreach ($listPlugin as $Plugin) {
            $command = "{$this->node} {$this->ltpm} install {$Plugin['filename']}";

            exec($command,$output);

            Artisan::call('lt-plugin:update',['--vendor-name'=> $Plugin['vendor'].','.$Plugin['name'], '--silent' => true]);

            Artisan::call('lt-migration:up',['--vendor-name'=>$Plugin['vendor'].','.$Plugin['name'], '--silent' => true]);
        }
    }

    /**
     * This method create file into config folder
     */
    public function copyFiles() {

        $customAction = File::get(__DIR__.'/../Services/stub/config/customAction.php.stub');

        $dbexporter = File::get(__DIR__.'/../Services/stub/config/dbexporter.php.stub');

        $pathCustom = config_path().'/customAction.php';

        $pathDbExporter = config_path().'/dbexporter.php';

        $this->createFile($pathCustom,$customAction);

        $this->createFile($pathDbExporter,$dbexporter);

    }

    public function setupEnvFile($connection,$host,$port,$DbName, $DbUsername, $DbPassword) {

        $this->loadEnv();

        $this->ENV['DB_CONNECTION'] = $connection;
        $this->ENV['DB_HOST'] = $host;
        $this->ENV['DB_PORT'] = $port;
        $this->ENV['DB_DATABASE'] = $DbName;
        $this->ENV['DB_USERNAME'] = $DbUsername;
        $this->ENV['DB_PASSWORD'] = $DbPassword;

        $this->saveOnConfig();

        $this->writeEnvFile();
    }

    protected function loadEnv() {

        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file(base_path().'/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        foreach ($lines as $data) {
            list($key,$value) = explode('=', $data,2);

            $this->ENV[$key] = $value;
        }
    }

    protected function writeEnvFile() {
        $newLines = [];

        foreach ($this->ENV as $key => $value) {

            $newLines[] = "{$key}={$value}";
            if($key=='APP_URL' || $key == 'DB_PASSWORD' || $key == 'QUEUE_DRIVER' || $key == 'REDIS_PORT' || $key == 'MAIL_ENCRYPTION' || $key == 'PUSHER_APP_SECRET' || $key == 'LTPM') {
                $newLines[] = '';
            }
        }

        $raw = implode("\n", $newLines);

        $this->createFile(base_path().'/.env',$raw);
    }

    protected function saveOnConfig() {
        $function = ucfirst($this->ENV['DB_CONNECTION']);

        $method = "save{$function}Config";

        $this->$method();

        foreach ($this->ENV as $key => $value) {
            $_ENV[$key] = $value;
        }

        $this->database->reconnect();
    }

    protected function saveMysqlConfig() {
        config(['database.default' => $this->ENV['DB_CONNECTION']]);
        config(['database.connections.mysql.host' => $this->ENV['DB_HOST']]);
        config(['database.connections.mysql.port' => $this->ENV['DB_PORT']]);
        config(['database.connections.mysql.database' => $this->ENV['DB_DATABASE']]);
        config(['database.connections.mysql.username' => $this->ENV['DB_USERNAME']]);
        config(['database.connections.mysql.password' => $this->ENV['DB_PASSWORD']]);
    }

    protected function saveSqliteConfig() {
        config(['database.default' => $this->ENV['DB_CONNECTION']]);
        config(['database.connections.sqlite.database' => $this->ENV['DB_DATABASE']]);
    }

    protected function savePgsqlConfig() {

        config(['database.default' => $this->ENV['DB_CONNECTION']]);
        config(['database.connections.pgsql.host' => $this->ENV['DB_HOST']]);
        config(['database.connections.pgsql.port' => $this->ENV['DB_PORT']]);
        config(['database.connections.pgsql.database' => $this->ENV['DB_DATABASE']]);
        config(['database.connections.pgsql.username' => $this->ENV['DB_USERNAME']]);
        config(['database.connections.pgsql.password' => $this->ENV['DB_PASSWORD']]);
    }

    protected function saveSqlsrvConfig() {
        config(['database.default' => $this->ENV['DB_CONNECTION']]);
        config(['database.connections.sqlsrv.host' => $this->ENV['DB_HOST']]);
        config(['database.connections.sqlsrv.port' => $this->ENV['DB_PORT']]);
        config(['database.connections.sqlsrv.database' => $this->ENV['DB_DATABASE']]);
        config(['database.connections.sqlsrv.username' => $this->ENV['DB_USERNAME']]);
        config(['database.connections.sqlsrv.password' => $this->ENV['DB_PASSWORD']]);
    }

    /**
     * This method put data into file and create it
     * @param $path
     * @param $data
     */
    protected function createFile($path,$data) {
        File::put($path,$data);
    }

    /**
     * This method sanitize the list of plugins
     * @return array
     */
    protected function sanitizePluginsArray() {
        $response = [];

        foreach ($this->plugins as $vendorName => $version) {

                list($vendor,$name) = explode('@',$vendorName,2);

                $response[] = [
                    "vendor"    => $vendor,
                    "name"      => $name,
                    "filename"  => ltpm()->getFileName($vendor,$name,$version)
                ];
        }

        return $response;
    }
}