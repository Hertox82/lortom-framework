<?php
/**
 * User: hernan
 * Date: 16/09/2020
 * Time: 14:31
 */

namespace LTFramework\Commands;


use Illuminate\Console\Command;
use LTFramework\Contracts\RouteCacheable;
use File;

class CacheRoute extends Command {

    protected $signature = "lt-route:cache";

    protected $description = "this command cache the lortom routes";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {

        $this->comment('Lortom is ready to cache your Routes ...');

        sleep(2);

        // Controllo se c'è qualcosa salvato nel database

        $page = resolve(RouteCacheable::class);
   
        $arrData = $page->getAllCacheable();
        
        if($arrData and count($arrData) > 0) {

            $fileName = config_path().'/route_cached.php';

            $source = "<?php \n return \n ".varexport($arrData,true).";";

            File::put($fileName, $source);
        }

        // invece se trovi qualcosa allora scrivi il file in config/route_cached.php

        $this->info('Reading the DB information');

        
        $this->info('Well done! The Routes are cached! If you add other route, remember to refresh it');
    }
}