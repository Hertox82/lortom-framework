<?php 

namespace LTFramework\Services\Classes;

use File;

class MultiSiteManager {

    /**
     * @var Illuminate\Support\Collection 
     */
    protected $settings;

    /**
     * The constructor load the settings of MultiSite
     * @return void
     */
    public function __construct()
    {
        // carica il settaggio del sito
        $this->loadSettings();
    }

    /**
     * this function load the mulitsite settings
     * 
     * @return void
     */
    protected function loadSettings() {
        $sitesPath = config_path().'/sites.php';

        if(!File::exists($sitesPath)) 
        {   
            $this->settings = collect([]);
        } else {
            $collect = require $sitesPath;
            $this->settings = collect($collect);
        }
    }

    /**
     * This function return if the App is MulitSite with shared Database
     * @return boolean
     */
    public function isMultisite() {
        return $this->settings->isEmpty();
    }

    /**
     * This function return the Site ID
     * @return boolean
     */
    public function getIdSite() {
        return $this->settings->get('id_site') ? $this->settings->get('id_site') : '';//isset($this->settings['id_site']) ? $this->settings['id_site'] : '';
    }

    /**
     * This function check if Model is Marked as Unique for this Site
     * @param string
     * @return boolean
     */
    public function hasModelReadable($model) {
        return $this->hasModelAction($model,'read');
    }

    /**
     * This function check if Model is Marked as Unique for this Site
     * @param string
     * @return boolean
     */
    public function hasModelWriteable($model) {
        return $this->hasModelAction($model, 'write');
    }

    /**
     * This function is abstract of hasModelReadable and hasModelWriteable
     * @param string $model
     * @param string $action
     * @return boolean
     */
    protected function hasModelAction($model, $action) {
        if(! $this->settings->has($action))
            return false;
            
        return collect($this->settings->get($action))->contains($model);
    }
}
