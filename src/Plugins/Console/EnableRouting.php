<?php
/**
 * User: hernan
 * Date: 18/10/2017
 * Time: 09:48
 */

namespace LTFramework\Plugins\Console;

use Illuminate\Console\Command;
use LTFramework\Plugins\Compiler\PluginRoutingCompiler;

class EnableRouting extends Command
{
    /**
     * The Name and signature of the Console command
     * @var string
     */
    protected $signature = 'lt-routing:enable';

    protected $description = 'This command write the routes on file app.routing.ts';

    /**
     *
     * @var LTFramework\Plugins\Compiler\PluginRoutingCompiler
     */
    protected $compiler;

    public function __construct(PluginRoutingCompiler $plugin)
    {
        parent::__construct();

        $this->compiler = $plugin;
    }

    public function handle()
    {
        $this->info('Initialized, we are checking if exist any plugin');

        if(!file_exists($path = config_path('plugins.php')))
        {
            $this->error("file plugins.php in config/ not exist,\n please, install plugins.php using this command: lt-plugin:create");
        }
        else {
            $ArrayConfig = require $path;

            if(isset($ArrayConfig['plugins'])) {

                $this->info('Compiling app.routing.ts');
                $this->compiler->write($ArrayConfig['plugins']);
                $this->info('All is done!');
            }
            else
            {
                $this->error('there is an error syntax in plugin');
            }
        }
    }


}