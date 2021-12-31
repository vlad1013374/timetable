<?php
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