<?php
/**
 * @link http://canis.io
 *
 * @copyright Copyright (c) 2015 Canis
 * @license http://canis.io/license/
 */
namespace canis\sensorHub\components\instances;

use Yii;
use canis\broadcaster\eventTypes\EventType;
use canis\sensors\providers\ProviderInterface;

class ServiceReferenceInstance extends Instance
{
    const COLLECT_DEPTH = 3;
    
    public function getObjectType()
    {
        return 'serviceReference';
    }

    public function getParentObjects()
    {
        return $this->collectParentObjects(static::COLLECT_DEPTH);
    }

    public function getChildObjects()
    {
        return $this->collectChildObjects(static::COLLECT_DEPTH);
    }


    public function getComponentPackage()
    {
        $c = [];
        $c['sensors'] = $this->collectSensors(3)->package;
        $c['resources'] = $this->collectResources(1)->package;
        return $c;
    }

	static public function collectEventTypes()
    {
    	$events = [];
    	return $events;
    }
    
    public function getPackage()
    {
    	$package = parent::getPackage();

    	return $package;
    }
}