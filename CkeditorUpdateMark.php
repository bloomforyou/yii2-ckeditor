<?php
namespace bloomforyou\ckeditor;

use Yii;
use Yii\base\Event;
use yii\base\Component;
use bloomforyou\ckeditor\events\CkeditorUpdateMarkEvent;
use Yii\web\ServerErrorHttpException;

class CkeditorUpdateMark extends Component
{
	const EVENT_UPDATE='update';
	
	public static function update(CkeditorUpdateMarkEvent $event)
	{
		$table=Yii::$app->db->tablePrefix . 'ckeditorswfupload_uploaed_image';
		
		if ( ! $event->type || ! $event->typeId) {
			throw new ServerErrorHttpException('swfupload 参数不能为空');	
		}		
		
		$userId=0;
		if ( ! $event->userId){
			$userId=Yii::$app->user->id;
		} else {
			$userId=$event->userId;
		}
		if ( ! $userId){
			throw new ServerErrorHttpException('swfupload 请登录后再进行本次操作');
		}
	
		Yii::$app->db->createCommand()->update(
			$table,
			['type_id' => $event->typeId],
			['user_id' => $userId, 'type'=> strtolower($event->type), 'type_id'=> 0]			
			)
			->query();
	}
}