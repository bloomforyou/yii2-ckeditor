<?php
namespace bloomforyou\ckeditor;

use Yii;
use Yii\web\ServerErrorHttpException;

class CkeditorUploadImage
{	
	public $db;	
	public $type;
	public $typeId;
	public $userId;	
	public $uploadImage;
	
	public $imageFolder;
	public $imageFolderUrl;
	
	public $maxSize=1024;
	public $allowedImageSuffix = array('jpg', 'jpeg', 'gif', 'png');
	
	public function upload()
	{
		$table=Yii::$app->db->tablePrefix . 'ckeditorswfupload_uploaed_image';
		
		$this->maxSize=intval($this->maxSize);		
		
		if(isset($this->uploadImage['tmp_name']) 
			&& isset($this->uploadImage['name'])
			&& is_uploaded_file($this->uploadImage['tmp_name'])){
			$suffix = substr($this->uploadImage['name'], strrpos($this->uploadImage['name'], ".")+1, 100);
			
			if (! in_array($suffix, $this->allowedImageSuffix)){
				echo "ERROR:非法的文件后缀";
				exit(0);
			}
			
			$info = @getImageSize($this->uploadImage['tmp_name']);
			if ( ! is_array($info)){
				echo "ERROR:文件无法识别";
				exit(0);
			}
			
			if ($this->uploadImage['size']/1024 >= $this->maxSize){
				echo "ERROR:文件太大(最大{$this->imageMaxSize}K) " .$this->uploadImage['size'];
				exit(0);
			}
			
			$newName = md5(microtime(true) . "file:" . $this->uploadImage['tmp_name'] . rand(0, 100000)) . "." . $suffix;			
			$path = self::generalImageSavePath($this->imageFolder);			
			$dbPath = str_replace($this->imageFolder, '', $path);
			move_uploaded_file($this->uploadImage['tmp_name'], $path . $newName);			
						
			//保存到数据库			
			Yii::$app->db->createCommand()->insert($table, [
				'type'=> $this->type,
				'type_id'=> $this->typeId,
				'user_id'=> $this->userId,
				'uploaded_at'=> date("Y-m-d H:i:s", time()),
				'info'=> $this->uploadImage['name'],
				'path'=> $dbPath . $newName,
			])->query();
						
			//生成略缩图			
			$this->makeThumb($path . $newName);				
			echo $this->imageFolderUrl. $dbPath . $newName;
			exit(0);		
		}
	}
	
	private function insertRecord()
	{
		
	}
	
	public static function generalImageSavePath($root)
	{
		$year = date("Y", time());
		$month = date("m", time());
		$day = date("d", time());
		
		if ( ! is_dir($root)) {
			throw new ServerErrorHttpException("DIRECTOTY {" . $root. '} NOT FOUND');
		}
		
		$base = $root . $year . '/';
		if ( ! is_dir($base)) {
			mkdir($base, 0777);
		}
		
		$base = $base . $month . '/';
		if ( ! is_dir($base)) {
			mkdir($base, 0777);
		}
		
		$base = $base . $day . '/';
		if ( ! is_dir($base)) {
			mkdir($base, 0777);
		}
		
		return $base;
	}
	
	//生成略缩图	
	private function makeThumb($imagePath)
	{
		$suffix = substr($imagePath, strrpos($imagePath, ".")+1, 100);

		$size = getimagesize($imagePath);
		if (in_array($suffix, array('jpg', 'jpeg'))) {
			$rsc = imagecreatefromjpeg($imagePath);
		} else if(in_array($suffix, array('gif'))) {
			$rsc = imagecreatefromgif($imagePath);
		} else if(in_array($suffix, array('png'))) {
			$rsc = imagecreatefrompng($imagePath);
		}
		
		if($size[0] >= $size[1]) {
			$thumbWidth = 120;
			$thumbHeight = floor(120*$size[1]/$size[0]);
		} else {
			$thumbHeight = 120;
			$thumbWidth = floor(120*$size[0]/$size[1]);
		}		
	
		$thumbRsc = imagecreatetruecolor($thumbWidth, $thumbHeight);		
		imagecopyresampled($thumbRsc, $rsc, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $size[0], $size[1]);		
		
		imagejpeg($thumbRsc,$imagePath.".thumb.jpg",90);
	}
	
}