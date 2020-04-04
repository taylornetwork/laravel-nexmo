<?php


namespace TaylorNetwork\LaravelNexmo\Traits;


use Illuminate\Support\Facades\Config;

trait ActionsHaveDefaults
{
    /**
     * Get NCCO action defaults from config and/or database
     *
     * @param string $action
     * @param array $merge
     * @return array
     */
    public function getActionDefaults(string $action, array $merge = []): array
    {
        $ncco = [ 'action' => strtolower($action) ];

        if(!property_exists($this, 'actionDefaultsFromConfig') || $this->actionDefaultsFromConfig) {
            $ncco = array_merge($ncco, $this->getActionDefaultsFromConfig($action));
        }

        if(!property_exists($this, 'actionDefaultsFromDatabase') || $this->actionDefaultsFromDatabase) {
            $ncco = array_merge($ncco, $this->getActionDefaultsFromDatabase($action));
        }

        return array_merge($ncco, $merge);
    }

    /**
     * Get the action defaults from the config files
     *
     * @param string $action
     * @return array
     */
    public function getActionDefaultsFromConfig(string $action): array
    {
        $defaults = [];

        foreach(Config::get('ncco.defaults.actions.'.$action) as $key => $value) {
            if($value !== null) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    /**
     * Get the action defaults from database
     *
     * @param string $action
     * @return array
     */
    public function getActionDefaultsFromDatabase(string $action): array
    {
        $defaults = [];

         // @todo query DB

        return $defaults;
    }
}