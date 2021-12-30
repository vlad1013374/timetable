const emptyItem = {
	"id":null,
	"week_id": config.weekId,
	"day":null,
	"lesson_id": null,
	"class_id": null,
	"subject_id": null,
	"room_id": null,
	"teacher_id": null,
	"comment":null,
	"flags":"0"
};


function calcHash(){
	for(let i=0; i< timetable.length; i++){
		if(!timetable_hash.hasOwnProperty(timetable[i]['day'])){
			timetable_hash[timetable[i]['day']] = {};
		}
		
		if(!timetable_hash[timetable[i]['day']].hasOwnProperty(timetable[i]['lesson_id'])){
			timetable_hash[timetable[i]['day']][timetable[i]['lesson_id']] = {};
		}
		
		if(!timetable_hash[timetable[i]['day']][timetable[i]['lesson_id']].hasOwnProperty(timetable[i]['class_id'])){
			timetable_hash[timetable[i]['day']][timetable[i]['lesson_id']][timetable[i]['class_id']] = {};
		}
		
		timetable_hash[timetable[i]['day']][timetable[i]['lesson_id']][timetable[i]['class_id']][timetable[i]['id']] = timetable[i];
	}
}

function getDefaultId(){
	return Math.round(new Date().getTime() / 100) - 16100000000;
}

function init(){
	let defaultId = getDefaultId();
	const tpl = $("#tpl").text();
	
	$("td.subject").each(function(){
		const $el = $(this);
		const classId = $el.data("class-id");
		const lessonId = $el.data("lesson-id");
		const day = $el.data("day");
		
		if(timetable_hash[day] && timetable_hash[day][lessonId] && timetable_hash[day][lessonId][classId]){
			for(let id in timetable_hash[day][lessonId][classId]){
				const tt = timetable_hash[day][lessonId][classId][id];
				console.log(tt);
				$el.append(tpl.replaceAll("{no}",id));
				initItem(id, tt.subject_id, tt.teacher_id, tt.room_id, classId, lessonId, day);
			}
		} else {
			$el.append(tpl.replaceAll("{no}",defaultId));
			initItem(defaultId++, null, null, null, classId, lessonId, day);				
		}
		
		
	});
	
	window.note = $("#note").kendoNotification({
	position: {
		//pinned: true,
		top: 30,
		right: 30
	},
	autoHideAfter: 7000,
	stacking: "down",
	templates: [{                
                type: "OK",
                template: '<div class="note-success"><img src="includes/img/success-icon.png" /><div>#= message #</div></div>'
            }, {
                type: "ERROR",
                template: '<div class="note-error"><img src="includes/img/error-icon.png" /><div>#= message #</div></div>'
        }]
	}).data("kendoNotification");
}

function initItem(id, subjectId, teacherId, roomId, classId, lessonId, day){
	$("#is_" + id).kendoComboBox({
		dataSource: subjects,
		dataTextField: "name",
		dataValueField: "id",
		filter: "contains",
		value: subjectId,
		autoWidth: true,
		change: function(e){
			let val = e.sender.value();
			createNewItemIfNeed(day, lessonId, classId, id);			
			
			if(val){
				timetable_hash[day][lessonId][classId][id]['subject_id'] = val;
			} else {
				timetable_hash[day][lessonId][classId][id]['teacher_id'] = null;
				timetable_hash[day][lessonId][classId][id]['subject_id'] = null;
			}
		}
	});
	$("#ir_" + id).kendoComboBox({
		dataSource: rooms,
		dataTextField: "name",
		value: roomId,
		dataValueField: "id",
		change: function(e){
			let val = e.sender.value();
			createNewItemIfNeed(day, lessonId, classId, id);
						
			if(val){
				timetable_hash[day][lessonId][classId][id]['room_id'] = val;
			} else {
				timetable_hash[day][lessonId][classId][id]['room_id'] = null;
			}
		}
	});
	
	$("#it_" + id).kendoComboBox({
		cascadeFrom: "is_" + id,
		cascadeFromField: "subject_id",
		dataSource: teachers,
		dataTextField: "name",
		clearButton: false,
		value: teacherId,
		dataValueField: "id",
		autoWidth: true,
		change: function(e){	
			let val = e.sender.value();
			createNewItemIfNeed(day, lessonId, classId, id);
						
			if(val){
				timetable_hash[day][lessonId][classId][id]['teacher_id'] = val;
			} else {
				timetable_hash[day][lessonId][classId][id]['teacher_id'] = null;
			}
		},
		cascade: function(e, a, b) {
			//console.log(e);
			//console.log(a);
			//console.log(b);
			//console.log(e.sender.dataSource.data().length);
		  }					
	});				
	
}

function createNewItemIfNeed(day, lessonId, classId, id){
	if(!timetable_hash.hasOwnProperty(day)){
		timetable_hash[day] = {};
	}
	
	if(!timetable_hash[day].hasOwnProperty(lessonId)){
		timetable_hash[day][lessonId] = {};
	}
	
	if(!timetable_hash[day][lessonId].hasOwnProperty(classId)){
		timetable_hash[day][lessonId][classId] = {};
	}
	
	if(!timetable_hash[day][lessonId][classId][id]){
		let newObj = Object.assign({}, emptyItem);
		newObj['lesson_id'] = lessonId;
		newObj['class_id'] = classId;
		newObj['day'] = day;
		newObj['id'] = id;
		
		timetable_hash[day][lessonId][classId][id] = newObj;
		timetable.push(newObj);
	}
}

function addSubject($td){
	const tpl = $("#tpl").text();
	const classId = $td.data("class-id");
	const lessonId = $td.data("lesson-id");
	const day = $td.data("day");
	var defaultId = getDefaultId();
	
	$td.append(tpl.replaceAll("{no}",defaultId));
	initItem(defaultId, null, null, null, classId, lessonId, day);	
}

function copy($td, isfromright){
	let $from;
	if(isfromright)
		$from = $td.next();
	else
		$from = $td.prev();
	
	if(!$from.hasClass("subject"))
		return;
	
	$td.find(".subject-block").each(function(){
		let delId = this.getAttribute("data-item-id");
		let newArr = [];
		for(let i=0; i < timetable.length; i++){
			if(timetable[i]['id'] != delId){
				newArr.push(timetable[i]);
			}
		}
		timetable = newArr;
	});
	$td.empty();	
	
	const tpl = $("#tpl").text();
	const classId = $td.data("class-id");
	const lessonId = $td.data("lesson-id");
	const day = $td.data("day");
	var defaultId = getDefaultId();
	
	$from.find(".subject-block").each(function(){
		$td.append(tpl.replaceAll("{no}",defaultId));
		initItem(defaultId, null, null, null, classId, lessonId, day);
		
		let sid = this.getAttribute("data-item-id");
		let fromClassId = $from.data("class-id");
		$("#is_" + defaultId).data("kendoComboBox").value($("#is_" + sid).data("kendoComboBox").value());
		$("#ir_" + defaultId).data("kendoComboBox").value($("#ir_" + sid).data("kendoComboBox").value());
		$("#it_" + defaultId).data("kendoComboBox").value($("#it_" + sid).data("kendoComboBox").value());
		
		if(timetable_hash[day] && timetable_hash[day][lessonId] && timetable_hash[day][lessonId][fromClassId] && timetable_hash[day][lessonId][fromClassId][sid]){
			let newObj = Object.assign({}, timetable_hash[day][lessonId][fromClassId][sid]);
			newObj['id'] = defaultId;
			newObj['class_id'] = classId;
			timetable.push(newObj);
			if(!timetable_hash[day][lessonId][classId])
				timetable_hash[day][lessonId][classId] = {};
			timetable_hash[day][lessonId][classId][defaultId] = newObj;
		}	
		
		defaultId++;
	});	
}

function initContextMenu(){
	$("#menu").kendoContextMenu({
		target: "table",
		filter: "td.subject",
		animation: {
			open: { effects: "fadeIn" },
			duration: 500
		},
		select: function(e, t) {
			console.log(e);
			switch(e.item.getAttribute("data-command")){
				case 'add': addSubject($(e.target)); break;
				case 'copy:left': copy($(e.target)); break;
				case 'copy:right': copy($(e.target), true); break;
				case 'mark:online': break;
				case 'mark:optional': break;
				case 'mark:olympiad': break;
				case 'hi': alert("Привет!"); break;
			}
		}
	});
}

hashCode = function(s){
  return s.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);              
}

setInterval(function(){
	var dt = JSON.stringify(timetable);
	$.post( "editor-auto-save.php", {data: dt})
	  .done(function(ev, a, b) {
		var r = JSON.parse(ev);
		note.show({message: r.message}, r.status);
	  })
	  .fail(function() {
		alert( "error" );
	  })
}, config.autosavePeriodInMinutes * 60 * 1000);



$(".but").click(function() {
	var dt = JSON.stringify(timetable);
	$.post( "editor-save.php", {data: dt})
	  .done(function(ev, a, b) {
		var r = JSON.parse(ev);
		note.show({message: r.message}, r.status);
		
	  })
	  .fail(function() {
		alert( "error" );
	  })
})

