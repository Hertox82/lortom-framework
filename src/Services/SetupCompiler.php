<?php
/**
 * User: hernan
 * Date: 05/07/2018
 * Time: 15:37
 */

namespace LTFramework\Services;

use Illuminate\Support\Facades\Artisan;
use File;

class SetupCompiler {

    protected $plugins = [
        "Hardel@Dashboard" => "1.0.0",
        "Hardel@Settings" => "1.0.0",
        "Hardel@Plugin" => "1.0.0",
        "Hardel@Website" => "1.0.0"
    ];

    protected $node;

    protected $ltpm;

    protected $ENV = [];

    public function __construct()
    {
        $this->node = $_ENV['NODE_JS'];
        $this->ltpm = $_ENV['LTPM'];
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
        }
    }

    /**
     * This method create file into config folder
     */
    public function copyFiles() {

        $customAction = File::get(__DIR__.'../Services/stub/config/customAction.php.stub');

        $dbexporter = File::get(__DIR__.'../Services/stub/config/dbexporter.php.stub');

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