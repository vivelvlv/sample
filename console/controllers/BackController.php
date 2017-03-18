<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InitController
 *
 * @author Joe
 */

namespace console\controllers;
use Yii;
use backend\models\User;
use backend\models\Role;
use backend\models\LeaderShip;
use backend\models\Project;
use backend\models\Task;

use backend\models\Tracker;
use backend\models\TaskAction;
use backend\models\ProjectAction;
use backend\models\SubProject;
use backend\models\SubProjectAction;

class BackController extends \yii\console\Controller
{
	// public function actionProject()
	// {
	// 	$projects = Project::find()->all();
	// 	$now = strtotime(Yii::$app->formatter->asDate(time()-3*60*60, 'yyyy-MM-dd')) ;

	// 	foreach ($projects as $project)
	// 	 {
	// 	 	if($project->type != Project::TYPE_NORMAL)
	// 	 		continue;

	// 		echo "Process Project ".$project->project_sn."\n";

	// 		ProjectAction::log2($project->id,$project->executor,
 //                           ProjectAction::PROJECT_ACTION_STATUS_NEW,'',$project->created_at);

 //           if(isset($project->executor))
 //           {
 //                $taskExecutor = $project->projectExecutor;
	//            if(isset($taskExecutor))
	//             {
	//                 $addMsg = $taskExecutor->username.'['.$taskExecutor->work_no.']';
	//                 ProjectAction::log2($project->id,$project->executor,
	//                                 ProjectAction::PROJECT_ACTION_STATUS_ASSIGN,
	//                                 $addMsg,$project->created_at);
	//             }
 //           }

 //           	if($project->status != Project::PROJECT_STATUS_NOT_START)
	// 		{
				
	// 			ProjectAction::log2($project->id,$project->executor,
	//                                 ProjectAction::PROJECT_ACTION_STATUS_ONGOING,
	//                                 '',$project->created_at);
	// 		}

	// 	}

	// }

	// public function actionSubProject()
	// {
	// 	$subs = SubProject::find()->all();
	// 	$now = strtotime(Yii::$app->formatter->asDate(time()-3*60*60, 'yyyy-MM-dd')) ;

	// 	foreach ($subs as $sub)
	// 	 {
	// 	 	$project = $sub->project;
	// 	 	if($project->type != Project::TYPE_NORMAL)
	// 	 		continue;

	// 		echo "Process Project ".$sub->id."\n";

	// 		SubProjectAction::log2($sub->id,$project->executor,
 //                           ProjectAction::PROJECT_ACTION_STATUS_NEW,'',$sub->created_at);

 //           if(isset($sub->executor))
 //           {
 //                $taskExecutor = $sub->subProjectExecutor;
	//            if(isset($taskExecutor))
	//             {
	//                 $addMsg = $taskExecutor->username.'['.$taskExecutor->work_no.']';
	//                 SubProjectAction::log2($sub->id,$project->executor,
	//                                 ProjectAction::PROJECT_ACTION_STATUS_ASSIGN,
	//                                 $addMsg,$sub->created_at);
	//             }
 //           }

 //           	if($sub->status != Project::PROJECT_STATUS_NOT_START)
	// 		{
				
	// 			SubProjectAction::log2($sub->id,$sub->executor,
	//                                 ProjectAction::PROJECT_ACTION_STATUS_ONGOING,
	//                                 '',$sub->created_at);
	// 		}

	// 	}

	// }


	// public function actionTask()
	// {
	// 	$tasks = Task::find()->all();
   
	// 	$now = strtotime(Yii::$app->formatter->asDate(time()-3*60*60, 'yyyy-MM-dd')) ;


	// 	foreach ($tasks as $task) 
	// 	{
	// 		echo "Process Task ".$task->id . "\n";

	// 		//Task log
	// 		$project = $task->project;

	// 		if(isset($project) && $project->type == Project::TYPE_NORMAL)
	// 		{
	// 			echo "Task log \n";
	// 			$create_time = $project->created_at;
	// 			$creator = $project->executor;
	// 			$sub_project = $task->subProject;
	// 			if(isset($sub_project))
	// 			{
	// 			   $create_time = $sub_project->created_at;
	// 			   $creator = $sub_project->executor;
	// 			}

	// 		    TaskAction::log2($task->id,$creator,
 //                           TaskAction::TASK_ACTION_STATUS_NEW,'',$create_time);

	//            if(isset($task->executor))
	//            {

	// 	           $taskExecutor = $task->taskExecutor;
	// 	           if(isset($taskExecutor))
	// 	            {
	// 	                $addMsg = $taskExecutor->username.'['.$taskExecutor->work_no.']';
	// 	                TaskAction::log2($task->id,$creator,
	// 	                                TaskAction::TASK_ACTION_STATUS_ASSIGN,
	// 	                                $addMsg,$create_time);
	// 	            }
	// 	        }
	// 		}

	// 		//actual start time
	// 		$needSave = false;

	// 		if($task->status != Task::TASK_STATUS_NOT_START && $project->type == Project::TYPE_NORMAL)
	// 		{
	// 			echo "actual start time \n";
	// 			$task->actual_start_date = $task->plan_start_date;

	// 			TaskAction::log2($task->id,$task->executor,
	//                                 TaskAction::TASK_ACTION_STATUS_ONGOING,
	//                                 '',$task->actual_start_date);
	// 			$needSave = true;
	// 		}

	// 		//actual end time
	// 		if($task->status == Task::TASK_STATUS_COMPLETE && $project->type == Project::TYPE_NORMAL)
	// 		{
	// 			echo "actual end time \n";

	// 			$task->actual_end_date = $task->plan_end_date <= $now 
	// 			                        ?  $task->plan_end_date
	// 			                        : $now;
	// 			TaskAction::log2($task->id,$task->executor,
	//                                 TaskAction::TASK_ACTION_STATUS_COMPLETE,
	//                                 '',$task->actual_end_date);
	// 			$needSave = true;
	// 		}



	// 		//actual work time
	// 		$total = 0;
	// 		$trackers = Tracker::findAll(['task_id'=>$task->id,'status'=>Tracker::TRACKER_STATUS_CHECK_PASS]);
	// 		if(isset($trackers))
	// 		{
	// 			foreach ($trackers as $tracker) 
	// 			{
	// 				$total += $tracker->work_time;
	// 			}
	// 		}
	// 		if($total > 0)
	// 		{
	// 		   echo "actual work time \n";
	// 		   $task->actual_work_time = $total;
	// 		   $needSave = true;
	// 		}

	// 		if($needSave )
	// 		{
	// 			$task->save();
	// 			echo "Save .... \n";
	// 		}
	// 		echo "Successfully Task ".$task->id ."\n";
	// 	}


	// 	echo "....Finished .....\n";
	// }
}
