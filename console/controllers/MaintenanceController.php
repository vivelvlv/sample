<?php

namespace console\controllers;

use \yii\console\Controller;
use \yii\helpers\Console;
use \backend\components\Maintenance;

/*
  run :  yii  maintenance/backup-database
*/

class MaintenanceController extends Controller
{
    public function actionBackupDatabase()
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'TRC00');

        $obj = new Maintenance();
        $ret = $obj->launchRemoteBackup('182.254.217.75','tracker', 'tracker', 'tracker', $tmpfname);
       // $ret = $obj->launchRemoteBackup('localhost','tracker', 'root', '123456', $tmpfname);
        if($ret['exitCode'] == 0)
        {
            $this->stdOut("OK\n");        
            $this->stdOut(sprintf("Backup successfully stored in: %s\n", $tmpfname));        
        }
        else
        {
            $this->stdOut("ERR\n");
        }
        
        // equivalent to return 0;
        return $ret['exitCode'];
    }
    

    public function actionBackupDatabaseAndSendEmail()
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'FOO'); // good
        $obj = new Maintenance();
        $ret = $obj->launchBackup('username', 'password', 'database_name', $tmpfname);

        $emailAttachment = null;
        if($ret['exitCode'] == 0)
        {
            $this->stdOut("OK\n");        
            $this->stdOut(sprintf("Backup successfully stored in: %s\n", $tmpfname));
            
            $textEmail = 'Backup database successful! Find it in attachment';
            $emailAttachment = $tmpfname;
        }
        else
        {
            $this->stdOut("ERR\n");
                    
            $textEmail = 'Error in backup database! Check it!';
        }
        
        $emailMsg = Yii::$app->mailer->compose()
            ->setFrom('from@example.com')
            ->setTo('to@example.com')
            ->setSubject('Backup database')
            ->setTextBody($textEmail);
            
        if($emailAttachment!=null) $emailMsg->attach($emailAttachment, ['fileName' => 'backup_db.sql']);
        $emailMsg->send();            
        
        // equivalent to return 0;
        return $ret['exitCode'];
    }

}

?>