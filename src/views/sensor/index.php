<?php
/**
 * @var yii\base\View
 */
canis\sensorHub\components\web\assetBundles\MonitorAsset::register($this);

ArrayHelper::multisort($tasks, 'title');
$this->title = $config['title'];
$this->params['breadcrumbs'][] = ['label' => $this->title];


