<?php
function add_week($week_json)
{
	$week = json_decode($week_json);
	/*$week_is_num = R::find('weeks', 'number = ?', [$week->number]);
	$week_is_mon = R::find('weeks', 'start = ?', [$week->start]);
	if($week_is_num){
		return false; 
	}elseif($week_is_mon){
		return false; 
	}else{*/
		$week_db = R::dispense('weeks');
		$week_db->number = $week->number;
		$week_db->start = $week->start;
		$week_db->stop = $week->stop;
		$week_db->comment = $week->comment;
		R::store($week_db);
		$new_id = R::getInsertID();
		if(!empty($week->copy)){
			$week_new = R::getAll('SELECT * FROM weeks where `id` = ?', [$new_id] );
			$copy_week = R::getAll('SELECT * FROM weeks where id = ?', [$week->copy]);
			$copy_week_datas = R::getAll('SELECT * FROM timetables where week_id = ?', [$week->copy]);
			$d1 = new DateTime($copy_week[0]['start']);
			$d2 = new DateTime($week->start);
			$interval = $d2->diff($d1);
			$diff = $interval->days;
			foreach ($copy_week_datas as $copy_week_data) {
				
				$day = date("Y-m-d", strtotime($copy_week_data['day'].'+ '.$diff.' days'));

				$timetable = R::dispense('timetables');
				$timetable->week_id = $week_new[0]['id'];
				$timetable->day = $day;
				$timetable->lesson_id =  $copy_week_data['lesson_id'];
				$timetable->class_id = $copy_week_data['class_id'];
				$timetable->subject_id = $copy_week_data['subject_id'];
				$timetable->room_id = $copy_week_data['room_id'];
				$timetable->teacher_id = $copy_week_data['teacher_id'];
				$timetable->comment = $copy_week_data['comment'];
				$timetable->flags = $copy_week_data['flags'];
				$timetable->link = $copy_week_data['link'];
				$timetable->code = $copy_week_data['code'];
				R::store($timetable);
			}
		}
		return true;
	//}
	
}
function edit_week($week_json)
{
	$week = json_decode($week_json);
	$week_db = R::load('weeks', $week->id);
	$week_db->start = $week->start;
	$week_db->stop = $week->stop;
	$week_db->comment = $week->comment;
	R::store($week_db);
}

function add_active_week($week_id)
{
	R::exec('UPDATE weeks set is_active = "0"');
	R::exec('UPDATE weeks set is_active = "1" where id= ?',[$week_id]);
	return true;
}

function delete_week($week_id)
{
	$week = R::load('weeks', $week_id);
	R::exec('delete from timetables where week_id = ?', [$week_id]);
	R::trash($week);
}

function save($data_json)
{
	$datas = json_decode($data_json);
	
	$log = R::dispense('logs');
	$log->data = $data_json;
	$log->type = 'manual';
	R::store($log);
	R::exec( 'delete from timetables where week_id = ?', [$datas[0]->week_id] );
	R::debug( TRUE );
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

function addNewTeacher($data)
{

	 $db_t = R::dispense('teachers');
     $db_t->name = $data['name'];
     R::store($db_t);

     $new_teacher_id = R::getInsertID();
      foreach ($data['subjects'] as $sub) {
         R::exec('INSERT INTO teacher_subjects (teacher_id, subject_id) values(?,?)', [$new_teacher_id, $sub]);
      } 
}

function addNewSubject($data)
{

	 $db_s = R::dispense('subjects');
   $db_s->name= $data['name'];
	 $db_s->short_name= $data['short_name'];
	 $db_s->default_room_id = empty($data['aud']) ? null : $data['aud'];
	 R::store($db_s);
}
function addNewRoom($data)
{

	 $db_a = R::dispense('rooms');
   $db_a->name = $data['number'];
   $db_a->capacity = $data['capacity'];
   R::store($db_a);
}
function addNewLink($data)
{
	 $data['lesson'] == ''? $data['lesson'] = null : $data['lesson'] = $data['lesson'] ;
	 $data['weekDay'] == ''? $data['weekDay'] = null : $data['weekDay']=$data['weekDay'];
	 $datas = array($data['teacher'], $data['subject'], $data['class'], $data['lesson'], $data['weekDay']);
      R::exec('INSERT INTO links (`teacher_id`,`subject_id`,`class_id`,`lesson_id`,`week_day`) values(? , ?, ?, ?, ?)
        ', $datas);
}
function removeLink($data)
{
	R::exec('DELETE FROM links WHERE teacher_id =? and subject_id =? and class_id =?', [$data['teacher'], $data['subject'], $data['class']]);
}


function editTeacher($data){
	if (trim($data['name']) != '') {
        $update_name =R::load('teachers', $data['id']);
        $update_name->name = $data['name'];
        R::store($update_name);
      }
      R::exec('DELETE FROM teacher_subjects WHERE teacher_id = ?', [$data['id']]);
      foreach ($data['subjects'] as $edit_sub_id) {
          R::exec('INSERT INTO teacher_subjects (teacher_id, subject_id) values(?,?)',[$data['id'], $edit_sub_id] );
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