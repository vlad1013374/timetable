<?php
function add_week($week_json)
{
	$week = json_decode($week_json);
	$week_is_num = R::find('weeks', 'number = ?', [$week->number]);
	$week_is_mon = R::find('weeks', 'start = ?', [$week->start]);
	if($week_is_num){
		return false; 
	}elseif($week_is_mon){
		return false; 
	}else{
		$week_db = R::dispense('weeks');
		$week_db->number = $week->number;
		$week_db->start = $week->start;
		$week_db->stop = $week->stop;
		R::store($week_db);
		return true;
	}
	
}
function add_active_week($week_id)
{
	R::exec('UPDATE weeks set is_active = "0"');
	$week_db = R::load('weeks', $week_id);
	$week_db->is_active = "1";
	R::store($week_db);
	return true;
}

function save($data_json)
{
	$datas = json_decode($data_json);
	
	$log = R::dispense('logs');
	$log->data = $data_json;
	$log->type = 'manual';
	R::store($log);
	R::exec( 'delete from timetables where week_id = ?', [$datas[0]->week_id] );
	if(count($datas) > 0)	
		foreach ($datas as $data) {			
			
			$timetable = R::dispense('timetables');
			$timetable->week_id = $data->week_id;
			$timetable->day = $data->day;
			$timetable->lesson_id =  $data->lesson_id;
			$timetable->class_id = $data->class_id;
			$timetable->subject_id = $data->subject_id;
			$timetable->room_id = $data->room_id;
			$timetable->teacher_id = $data->teacher_id;
			$timetable->comment = $data->comment;
			$timetable->flags = $data->flags;
			R::store($timetable);
			
		}
	
		
}
function auto_save($data_json_auto)
{
	$datas = json_decode($data_json_auto);
		
	$log = R::dispense('logs');
	$log->data = $data_json_auto;
	$log->type = 'auto';
	R::store($log); 
	foreach ($datas as $data) {		
		$timetable = R::dispense('autosave');		
		$timetable->week_id = $data->week_id;
		$timetable->day = $data->day;
		$timetable->lesson_id =  $data->lesson_id;
		$timetable->class_id = $data->class_id;
		$timetable->subject_id = $data->subject_id;
		$timetable->room_id = $data->room_id;
		$timetable->teacher_id = $data->teacher_id;
		$timetable->comment = $data->comment;
		$timetable->flags = $data->flags;
		R::store($timetable);		
	}		
}

const DAY_NAMES = array('', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье');

class WeekEditModel {
	public $number;
	
	public $days = array();
	
	function __construct($week = null, $lessons = null) {

		if($week != null){
			$this->number = $week["number"];
			
			$interval = new DateInterval('P1D');
			$daterange = new DatePeriod(new DateTime($week["start"]), $interval, new DateTime($week["stop"]));
			foreach($daterange as $date){
				array_push($this->days, new DayEditModel($date, $lessons));
			}
		}
   }	
}


class DayEditModel {
	public $date;
	public $dayName;
	
	public $lessons = array();
	
	function __construct($date = null, $lessons = null) {
		if($date != null){
			$this->date = $date;
			$this->dayName = DAY_NAMES[date("N", $date->getTimestamp())];
			
			foreach($lessons as $lesson){
				array_push($this->lessons, new LessonEditModel($lesson ));
			}
		}
   }	
}

class LessonEditModel {
	public $lessonId;
	public $start;
	public $stop;
	
	public $items = array();
	
	function __construct($lesson = null/*, $class_id = null*/) {
		//print_r($lesson); die();
		if($lesson != null /*&& $class_id != null*/){
			$this->lessonId = $lesson['id'];
			$this->start = $lesson['start'];
			$this->stop = $lesson['stop'];
			//$this->classId = $class_id;
			array_push($this->items, new ItemEditModel());
			///TODO
		}
   }	
}

class ItemEditModel {
	
	function __construct(){
		$this->classId = 2; 
	}
	
	public $subjectId;
	public $roomId;
	public $teacherId;
	public $classId;
}







?>