<?php
/**
 * User: hernan
 * Date: 17/04/2018
 * Time: 10:24
 */

namespace LTFramework\Template\Console;

use LTFramework\Exceptions\VNException;
use LTFramework\Support\LortomCommand as Command;
use LTFramework\Template\Compiler\TemplateCreateCompiler;
use File;
use Artisan;

class CreateTemplate extends Command
{
    /**
     * This is a command in order to create a Template
     * @var string
     */
    protected $signature = "lt-template:create {--vendor-name= : Pass the Vendor,Name for your Template!} {--silent}";

    protected $description = 'In Order to create a template digit --vendor-name=Vendor,Name';

    /**
     * @var \LTFramework\Template\Compiler\TemplateCreateCompiler
     */
    protected $compiler;


    public function __construct(TemplateCreateCompiler $compiler)
    {
        parent::__construct();
        $this->compiler = $compiler;
    }

    public function handle()
    {
        //check the Vendor-Name format
        $silent = $this->option('silent');
        try {
            //get vendor and name
            list($vendor, $name) = $this->extrapolateVendorName($this->option('vendor-name'),$silent);

        }catch (VNException $e) {
            $this->error($e->getMessage());
            return;
        }


        if(!$silent) {
            if($this->confirm("This is the Vendor = {$vendor}, the Name= {$name} of template that you choice, Do you wish to continue?")) {
                $this->exeCreate($vendor,$name);
            }
            else {
                $this->info("Ok, try again! see ya!");
            }

        } else {
            $this->exeCreate($vendor,$name);
        }

    }

    protected function exeCreate($vendor, $name) {
        //get the Name sanitize
        $name = $this->extrapolateName($name);
        //check if folder really exist
        $this->checkIfPathNotExists($vendor,$name,"Template");

        $this->compiler->setVendorName($vendor, $name)
            ->writingDirectory()
            ->writingConfigJSON()
            ->writingModelClass()
            ->writingAutoload();

            //adding info into ltpm.config.json
        $this->addTemplateToLtpm($vendor, $name, "0.1.0");

        //After this call Adding 4 plugin base
        Artisan::call('lt-template:add-plugin',['--vendor-name'=> $vendor.','.$name, '--silent' => true, '--name-plugin' => 'Hardel,Dashboard']);
        Artisan::call('lt-template:add-plugin',['--vendor-name'=> $vendor.','.$name, '--silent' => true, '--name-plugin' => 'Hardel,Settings']);
        Artisan::call('lt-template:add-plugin',['--vendor-name'=> $vendor.','.$name, '--silent' => true, '--name-plugin' => 'Hardel,Plugin']);
        Artisan::call('lt-template:add-plugin',['--vendor-name'=> $vendor.','.$name, '--silent' => true, '--name-plugin' => 'Hardel,Website']);

        $this->info('Please, be sure to make the composer dump-autoload!');
    }


    protected function addTemplateToLtpm($vendor,$name, $version) {
        $ltpm = base_path().'/ltpm.config.json';
        if(File::exists($ltpm)) {
            $ltpmJSON =  json_decode(File::get($ltpm),true);

            $ltpmJSON['template'][] = [
                "vendor" =>$vendor,
                "name" =>$name,
                "version" => $version,
                "active" => false
            ];
            $encoded = json_encode($ltpmJSON,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            File::put($ltpm,$encoded);
        }
    }


}