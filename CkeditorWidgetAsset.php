<?php
namespace bloomforyou\ckeditor\assets;  
  
use yii\web\AssetBundle;  
  
class CkeditorWidgetAsset extends AssetBundle  
{      
    public $sourcePath = '@vendor/bloomforyou/yii2-ckeditor/js';  
    
    public $css = [  
    	//'magnific-popup.css',  
    ];  
    public $js = [  
    	'ckeditor-4.5.6/ckeditor.js',  
    	'ckeditorSwfupload.js', 
    	'swfUpload/js/swfupload.js',
    	'swfUpload/js/swfupload.queue.js' ,
    	'swfUpload/js/fileprogress.js' ,
    	'swfUpload/js/handlers.js' ,    	
    ];  
    public $depends = [  
		'yii\web\JqueryAsset',  
    ];  
    
    //ÉèÖÃ
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}  