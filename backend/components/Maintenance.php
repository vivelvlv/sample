<?php 

namespace backend\components;

use Yii;
use yii\base\Component;

/**
*  
*/
class Maintenance extends Component
{
	
   public function launchBackup($database, $username, $password, $pathDestSqlFile)
   {
        $cmd = sprintf('mysqldump -u %s -p%s %s > %s', $username, $password, $database, $pathDestSqlFile);
        $outputLines = [];
        exec($cmd, $outputLines, $exitCode);

        return ['cmd' => $cmd, 'exitCode' => $exitCode, 'outputLines' => $outputLines];        
   }

   public function launchRemoteBackup($host,$database, $username, $password, $pathDestSqlFile)
   {
        $cmd = sprintf('mysqldump -h %s -u %s -p%s %s > %s', $host,$username, $password, $database, $pathDestSqlFile);
        $outputLines = [];
        exec($cmd, $outputLines, $exitCode);

        return ['cmd' => $cmd, 'exitCode' => $exitCode, 'outputLines' => $outputLines];        
   }
}