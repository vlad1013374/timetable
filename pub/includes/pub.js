

for (var i = 0; i <= timetable.length - 1; i++){
	$("tbody td").each(function() {
		if($(this).attr("data-class-id") == timetable[i].class_id && 
		   $(this).attr("data-lesson-id") == timetable[i].lesson_id && 
		   $(this).attr("data-day") == timetable[i].day){
		   	if($(this).hasClass("teacher")){
		   		$(this).append('<div class="flag-'+timetable[i].flags+'">'+timetable[i].sub_name+'</div>');
		   	
		   		
		   	}else if($(this).hasClass("subject")){
		   		$(this).append('<div class="flag-'+timetable[i].flags+'">'+timetable[i].short_name+'</div>');
		   		

		   	}else if($(this).hasClass("classroom")){
		   		$(this).append('<div class="flag-'+timetable[i].flags+'">'+timetable[i].room_id+'</div>');
		   		
		   		
		   	}
			/*$(this).addClass("flag-"+timetable[i].flags);*/
		}
	})
}


