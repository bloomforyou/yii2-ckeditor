<?php
namespace bloomforyou\ckeditor;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;

class BootstrapClass implements BootstrapInterface
{
    public function bootstrap($app)
    {
        //EVENT_BEFORE_REQUEST
        $app->on(Application::STATE_INIT, function ($app) {
             // do something here
            $table=Yii::$app->db->tablePrefix . 'ckeditorswfupload_uploaed_image'; 
                        
            Yii::$app->db->createCommand()->dropTable($table)->query();
             
            //only for mysql
         	Yii::$app->db->createCommand()->createTable($table, [
				    'id' 		=> 'pk',
				    'type_id' 	=> 'integer',
				    'type' 		=> 'char(50)',
				    'user_id' 	=> 'integer',
				    'info'		=> 'string',
				    'path'		=> 'string',
				    'uploaded_at'=> 'datetime',
				])				
				->query();
				
			Yii::$app->db->createCommand()->createIndex('type', $table, "type")->query();
			Yii::$app->db->createCommand()->createIndex('type_id', $table, "type_id")->query();
			Yii::$app->db->createCommand()->createIndex('user_id', $table, "user_id")->query();
			Yii::$app->db->createCommand()->createIndex('uploaded_at', $table, "uploaded_at")->query();			
        });
    }
}