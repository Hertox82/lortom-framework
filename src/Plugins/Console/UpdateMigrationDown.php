<?php
/**
 * User: hernan
 * Date: 13/11/2017
 * Time: 12:53
 */

namespace LTFramework\Plugins\Console;


use Illuminate\Console\Command;
use File;
use LTFramework\Plugins\Compiler\PluginUpdateCompiler;

class UpdateMigrationDown extends Command
{
    protected $signature = "lt-migration:down {--vendor-name=}";

    protected $description = "This command is to rollup a Migration from Plugin";

    /**
     * @var \LTFramework\Plugins\Compiler\PluginUpdateCompiler
     */
    protected $compiler;

    public function __construct(PluginUpdateCompiler $compiler)
    {
        parent::__construct();

        $this->compiler = $compiler;
    }

    public function handle()
    {
        $VendorName = $this->option('vendor-name');

        $vendor = '';
        $name = '';

        if(is_null($VendorName))
        {
            $vendor = $this->ask('Vendor Name?');
            $name   = $this->ask('Name of Plugin?');
        }
        else
        {
            $vendorName = explode(',',$VendorName);

            if(count($vendorName) != 2)
            {
                $vendor = $this->ask('Vendor Name?');
                $name   = $this->ask('Name of Plugin?');
            }
            else
            {
                $vendor = $vendorName[0];
                $name   = $vendorName[1];
            }
        }

        if($this->confirm("This is the Vendor = {$vendor}, the Name= {$name} of plugin that you choice to UPDATE, Do you wish to continue?"))
        {

            $name = str_replace('-',' ',$name);
            $name = ucwords($name);
            $name = str_replace(' ','',$name);

            $pathPlugin = base_path().'/angular-backend/src/plugins/';

            if(File::exists($pathPlugin.$vendor))
            {
                $pathVendor = $pathPlugin.$vendor.'/';

                if(!File::exists($pathVendor.$name))
                {
                    $this->info("This Plugin: {$name} in this Vendor: {$vendor} note exist! Please select other Name for your plugin");
                    return;
                }

                //launch migration up
                $this->compiler->setVendorName($vendor,$name)->migration('Down');


                $this->info("\n");
                $this->info("Ok! this Plugin : {$name} is updated!");
            }
            else
            {
                $this->info("this Vendor: {$vendor} not exist! Please select other Vendor for your plugin");
            }

        }
    }
}