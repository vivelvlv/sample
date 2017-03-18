<?php

namespace backend\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\base\InvalidConfigException;

/**
 *  PathComponent: used for path management.
 *  $imagePath: the root path for the upload images.
 *  $filePath: the root path for upload files
 *
*/

class UploadComponent extends Component
{
   /**
    * the root path of uploading image.
   */
   public $imageRootPath ;

   /**
    * the root url of uploading image.
   */
   public $imageRootUrl;

   /**
    * the root path of uploading file.
   */
   public $fileRootPath;

   /**
    * the root url of uploading file.
   */
   public $fileRootUrl;


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
    	parent::init();

        if ($this->imageRootPath === null) {
            throw new InvalidConfigException('The imageRootPath property must be set.');
        }
        if ($this->imageRootUrl === null) {
            throw new InvalidConfigException('The imageRootUrl property must be set.');
        }
        if ($this->fileRootPath === null) {
            throw new InvalidConfigException('The fileRootPath property must be set.');
        }
        if ($this->fileRootUrl === null) {
            throw new InvalidConfigException('The fileRootUrl property must be set.');
        }
    }

    /**
     * [UploadImage description]
     *
     * @param [type]  $model      [the model Object]
     * @param [type]  $originName [the attribute image name of the model]
     * @param [type]  $relativePath       [the relative save path]
     * @param boolean $isAbsUrl   [the return image path is abusolute url or not.]
     * @return mixed  if fail, return false; otherwise return the image file Url.
     */
    public function UploadImage($model,$originName,$relativePath,$isAbsUrl=false)
    {

    	// ensure the relativePath doesn't start and end with '/'
    	if(strpos($relativePath,'/') == 0)
    	{
    	   $relativePath = substr($relativePath,1);
    	}
    	if(substr($relativePath, -1) === '/')
    	{
    		$relativePath = substr($relativePath,0,strlen($relativePath) -1);
    	}

    	$root = $this->imageRootPath.'/'.$relativePath;

    	if(!is_dir($root))
        {
            if(!mkdir($root, 0777, true))
            {
                Yii::error( $$root.'does not exist and fail to create it using mkdir', __METHOD__);
                return false;
            }
        }

    	 $image = UploadedFile::getInstance($model,$originName);
    	 if(empty($image))
    	 {
    	 	Yii::error( 'Fail to get attribute name :'.$originName, __METHOD__);
            return false;
    	 }
         
         $ext = $image->getExtension();
         $imageName = time().rand(100,999).'.'.$ext;
         if($image->saveAs($root.'/'.$imageName))
         {
         	 if(!$isAbsUrl)
         	 {
         	 	return $relativePath.'/'.$imageName;
         	 }
         	 else
         	 {
         	 	return $this->imageRootUrl.$relativePath.'/'.$imageName;
         	 }
         }
         else
         {
         	 Yii::error( 'Fail to save image:'.$root.'/'.$imageName, __METHOD__);
             return false;
         }
    }

    /**
     * [removeImage description]
     *
     * @param [type]  $fileName      [the image file name ]
     * @return mixed  if fail, return false; otherwise return true.
     */

    public function removeImage($fileName)
    {
    	$fileName = $this->imageRootPath.'/'.$fileName;
    	if(is_file($fileName))
    	{
            unlink($fileName);
    	}
    }


    /**
     * [UploadFile description]
     *
     * @param [type]  $fileName [the file name in $FILES]
     * @param [type]  $relativePath       [the relative save path]
     * @param [type]  $saveFileName       [the file name will save ]
     * @param boolean $isAbsUrl   [the return file path is abusolute url or not.]
     * @return mixed  if fail, return false; otherwise return the file file Url.
     */
    public function UploadFile($fileName,$relativePath,$saveFileName,$isAbsUrl=false)
    {

         $file = UploadedFile::getInstanceByName($fileName);
         return $this->UploadFileInstance($file,$relativePath,$saveFileName,$isAbsUrl);
    }

    public function UploadFileInstance($file,$relativePath,$saveFileName,$isAbsUrl=false)
    {
         if(empty($file))
         {
            Yii::error( 'Fail to get attribute name :'.$relativePath, __METHOD__);
            return false;
         }

        // ensure the relativePath doesn't start and end with '/'
        if(strpos($relativePath,'/') == 0)
        {
           $relativePath = substr($relativePath,1);
        }
        if(substr($relativePath, -1) === '/')
        {
            $relativePath = substr($relativePath,0,strlen($relativePath) -1);
        }

        $root = $this->fileRootPath.'/'.$relativePath;

        if(!is_dir($root))
        {
            if(!mkdir($root, 0777, true))
            {
                Yii::error( $$root.'does not exist and fail to create it using mkdir', __METHOD__);
                return false;
            }
        }
         
         $ext = $file->getExtension();
         $saveName = $saveFileName;

         if(strtolower(substr($saveFileName,-strlen($ext)))!= strtolower($ext))
         {
            $saveName = $saveFileName.'.'.$ext;
         }
         
         if($file->saveAs($root.'/'.$saveName))
         {
             if(!$isAbsUrl)
             {
                return $relativePath.'/'.$saveName;
             }
             else
             {
                return $this->fileRootUrl.$relativePath.'/'.$saveName;
             }
         }
         else
         {
             Yii::error( 'Fail to save File:'.$root.'/'.$saveName, __METHOD__);
             return false;
         }
    }
}
