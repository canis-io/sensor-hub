<?php
/**
 * @link http://canis.io
 *
 * @copyright Copyright (c) 2015 Canis
 * @license http://canis.io/license/
 */

namespace canis\sensorHub\components\instances;

use Yii;
use canis\registry\models\Registry;

abstract class Collection extends \yii\base\Object
{
	public $type = 'children';
	protected $_model;
	protected $_objects = [];

	public function __sleep()
	{
		if (is_object($this->_model)) {
			$this->_model = $this->_model->id;
		}
		foreach ($this->_objects as $key => $item) {
			$this->_objects[$key][2] = null;
		}
		return array_keys((array) $this);
	}

	public function __wakeup()
	{
		if (!empty($this->_model)) {
			$this->_model = Registry::getObject($this->_model);
		}
		foreach ($this->_objects as $key => $item) {
			if ($item[2] === null) {
				$this->_objects[$key][2] = Registry::getObject($key, false);
			}
		}
	}

	public function setModel($model)
	{
		$this->_model = $model;
	}

	public function getModel()
	{
		if (!empty($this->_model) && !is_object($this->_model)) {
			$this->_model = Registry::getObject($this->_model);
		}
		return $this->_model;
	}

	public function getAll($maxDepth = false, $objectType = false)
	{
		if ($objectType !== false && !is_array($objectType)) {
			$objectType = [$objectType];
		}
		$objects = [];
		foreach ($this->_objects as $key => $item) {
			if ($maxDepth !== false && $maxDepth < $item[0]) {
				continue;
			}
			if ($objectType !== false && !in_array($item[1], $objectType)) {
				continue;
			}
			if (empty($item[2])) {
				$item[2] = $this->_objects[$key][2] = Registry::getObject($key);
			}
			$objects[$key] = $item[2];
		}

		return $objects;
	}

	public function add($depth, $objectType, $model, $data = [])
	{
		$this->_objects[$model->id] = [$depth, $objectType, $model->dataObject, $data];
	}

	abstract public function getChildPackageItems();
	abstract public function getParentPackageItems();

	public function getPackage($maxDepth = false, $objectType = false)
	{
		$package = [];
		if ($this->type === 'parents') {
			$package['items'] = $this->getParentPackageItems($maxDepth, $objectType);
		} else {
			$package['items'] = $this->getChildPackageItems($maxDepth, $objectType);
		}
		return $package;
	}
}