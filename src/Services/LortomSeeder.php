<?php
/**
 * User: hernan
 * Date: 28/06/2018
 * Time: 10:33
 */

namespace LTFramework\Services;

use Illuminate\Database\Seeder;

class LortomSeeder extends Seeder {


    /**
     * @var \LTFramework\Services\RepoSeeder
     */
    protected $seeder;


    protected $register = false;


    public function __construct(RepoSeeder $repo)
    {
        $this->seeder = $repo;

        if($this->register) {
            $this->registerSeeder(get_called_class());
        }

    }

    /**
     * @param $class
     */
    protected function registerSeeder($class) {

       $this->seeder->add($class);
    }


    /**
     * This function
     * @return array
     */
    protected function all() {

        return $this->seeder->all();
    }

}