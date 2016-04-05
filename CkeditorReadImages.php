<?php
namespace bloomforyou\ckeditor;

use Yii;
use Yii\web\ServerErrorHttpException;
use yii\helpers\Html;

class CkeditorReadImages
{	
	public $type;
	public $typeId;
	public $userId;
	public $imageFolderUrl;
	
	public function read()
	{
		$table=Yii::$app->db->tablePrefix . 'ckeditorswfupload_uploaed_image';
		
		$images = (new \yii\db\Query())
		    ->select('id, path, info')
		    ->from($table)
		    ->orderBy('id DESC')
		    ->where(['type' => $this->type, 'type_id' => $this->typeId, 'user_id'=> $this->userId])
		    ->all();
		
		foreach($images as $image){		
			$info = Html::encode($image['info']);
			$path = $this->imageFolderUrl . $image['path'];
		}		

		foreach($images as $image):?>
			<div class="swfupload_imagePrefix" style="width:80px;padding:5px;float:left;">
				<div style="width:80px;height:100px;overflow:hidden;border: solid 1px #7FAAFF; background-color: #E2EBFD;">
					<a title="<?php echo Html::encode($image['info']); ?>" style="display:block;width:80px;height:80px;overflow:hidden;background:url(<?php echo $this->imageFolderUrl . $image['path']; ?>.thumb.jpg) #F8FCFF no-repeat center center;">				
					</a>
					
					<div style="padding:0px 0 0 0;">
					<input type="checkbox" class="checkbox" style="" name="swfupload_chooseImages[]" value="<?php echo Yii::$app->params['imageFolderUrl'] . $image['path']; ?>" style="display:inline;"/>
					<a alt="<?php echo Html::encode($image['info']); ?>" title="<?php echo Html::encode($image['info']); ?>"><?php echo Html::encode($image['info']); ?></a>
					</div>					
				</div>
			</div>
		<?php endforeach;

	}
}