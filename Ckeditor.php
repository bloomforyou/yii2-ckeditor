<?php
namespace bloomforyou\ckeditor;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Widget;
use bloomforyou\ckeditor\assets\CkeditorWidgetAsset;
use yii\base\Application;

class Ckeditor extends Widget
{    
    public $model;	
	public $attribute;
	public $autogrow=true;
	
	public function run()
	{		
		$fileId = md5(get_class($this->model) . "[" . $this->attribute . "]");		
		$eleId = strtolower(basename(get_class($this->model)))."_".$this->attribute;
		
		echo Html::textArea(
			basename(get_class($this->model)) . "[" . $this->attribute . "]", 
			$this->model->{$this->attribute},
			['id'=> $eleId]
		);	
				 
   		CkeditorWidgetAsset::register($this->getView());   		
		$js = "CKEDITOR.replace( '".$eleId."',{
			language :'zh-cn',	
			skin :'kama',
			toolbarLocation :'bottom',		
			width:'100%',	
			extraPlugins : 'bloommultipleimageupload'
		});";
		
		CkeditorWidgetAsset::register($this->getView());
		
		echo $this->getView()->registerJs($js);
		
		echo Html::hiddenInput(
			'swfupload_content_type', 
			basename(get_class($this->model)),
			['id'=> 'swfupload_content_type']
		);	
		
		echo Html::hiddenInput(
			'swfupload_content_type_id', 
			isset($this->model->id) ? $this->model->id : 0,
			['id'=> 'swfupload_content_type_id']
		);	
		
		echo Html::hiddenInput(
			'swfupload_user_id', 
			isset(Yii::$app->user->id) ? Yii::$app->user->id : 0,
			['id'=> 'swfupload_user_id']
		);
		
		echo Html::hiddenInput(
			'swfuploadDefaultUrl', 
			Url::to(["ckeditor-swfupload/upload"]),
			['id'=> 'swfuploadDefaultUrl']
		);
		echo Html::hiddenInput(
			'swfuploadShowImageDefaultUrl', 
			Url::to(["ckeditor-swfupload/get-images"]),
			['id'=> 'swfuploadShowImageDefaultUrl']
		);
		echo Html::hiddenInput(
			'swfuploadBaseUrl', 
			Url::base(),
			['id'=> 'swfuploadBaseUrl']
		);
		
	}	
}