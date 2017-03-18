<?php

/**
 * Description of TimerController
 * use console to implement the timer action.
 * In linux, we can execute crontab -e
 *      30 21 * * * /usr/local/php/bin/php /your_project_path/yii timer/test
 *      The command will be executed at 21:30 every day.
 * @author Joe
 */

namespace console\controllers;

use Yii;
use backend\models\CodeType;

class TimerController extends \yii\console\Controller
{
   /**
     * Reset the code serial number in the CodeType model.
     * This serial number will be increased when applying new code.
     * But at 0 clock of every day, we need to reset to 0 again.
     */
   public function actionResetTodayCode()
   {
   	    Yii::$app->db
   	             ->createCommand()
   	             ->update(CodeType::tableName(), ['today_code' => 0])
   	             ->execute();
   }
}