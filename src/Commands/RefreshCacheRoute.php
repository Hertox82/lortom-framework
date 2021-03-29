<?php 

/**
 * User: hernan
 * Date: 21/09/2020
 * Time: 14:31
 */

namespace LTFramework\Commands;


use Illuminate\Console\Command;
use File;
use Artisan;

class RefreshCacheRoute extends Command {

    protected $signature = "lt-route:refresh-cache";

    protected $description = "this command refresh cache of lortom routes";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {

        $this->comment('Lortom is ready to refresh cache of your Routes ...');

        sleep(2);

        $this->comment('cleaning the cached routes...');
        if(File::exists($fileName = config_path().'/route_cached.php')) {
            File::delete($fileName);
            $this->info('route_cached.php just cleaned!');
            Artisan::call('lt-route:cache');
        } else {
            $this->info('file route_cached.php Not Found');
        }
    }
}