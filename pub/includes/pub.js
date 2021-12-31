


for (var i = 0; i <= timetable.length - 1; i++){
	$("tbody td").each(function() {
		if($(this).attr("data-class-id") == timetable[i].class_id && 
		   $(this).attr("data-lesson-id") == timetable[i].lesson_id && 
		   $(this).attr("data-day") == timetable[i].day){
		   	if($(this).hasClass("teacher")){
		   		$(this).append(timetable[i].name);
		   		
		   	}else if($(this).hasClass("subject")){
		   		$(this).append(timetable[i].short_name);

		   	}else if($(this).hasClass("classroom")){
		   		$(this).append(timetable[i].room_id);
		   	}
			$(this).addClass("flag-"+timetable[i].flags);
		}
	})
}


