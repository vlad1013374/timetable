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
	
	for(let i=0; i< classes.length; i++){
		class_hash[classes[i]['id']] = classes[i]['name'];
	}
}

function getDefaultId(){
	return window.globalId++;
}

function init(){
	window.globalId = Math.round(new Date().getTime() / 100) - 16100000000;	
	const tpl = $("#tpl").text();
	const tpl2 = $("#tpl-flags").text();
	
	$("td.subject").each(function(){
		const $el = $(this);
		const classId = $el.data("class-id");
		const lessonId = $el.data("lesson-id");
		const day = $el.data("day");
		
		if(timetable_hash[day] && timetable_hash[day][lessonId] && timetable_hash[day][lessonId][classId]){
			for(let id in timetable_hash[day][lessonId][classId]){
				const tt = timetable_hash[day][lessonId][classId][id];
				$el.append(tpl.replaceAll("{no}",id));
				initItem(id, tt.subject_id, tt.teacher_id, tt.room_id, classId, lessonId, day, tt.flags, tt.comment);
			}
		} else {
			let defaultId = getDefaultId();
			$el.append(tpl.replaceAll("{no}",defaultId));
			initItem(defaultId, null, null, null, classId, lessonId, day, 0, null);				
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

function initItem(id, subjectId, teacherId, roomId, classId, lessonId, day, flags, comment){

	var $subj = $("#is_" + id);
	var $it = $("#it_" + id);
	$subj.kendoComboBox({
		dataSource: subjects,
		dataTextField: "name",
		dataValueField: "id",
		filter: "startswith",
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
			
			if(val){
				let teacher = $it.data("kendoComboBox");
				let view = teacher.dataSource.view();
				let key1 = val + '-' + classId + '--';
				let key2 = val + '-' + classId + '-' + lessonId + '-';
				if(view.length == 1){
					teacher.value(view[0]['id']);
					timetable_hash[day][lessonId][classId][id]['teacher_id'] = view[0]['id'];
				} else if(links[key1]) {
					teacher.value(links[key1]);
					timetable_hash[day][lessonId][classId][id]['teacher_id'] = links[key1];
				} else if(links[key2]) {
					teacher.value(links[key2]);
					timetable_hash[day][lessonId][classId][id]['teacher_id'] = links[key2];
				}
			}
		}
	});
	$("#ir_" + id).kendoComboBox({
		dataSource: rooms,
		dataTextField: "name",
		value: roomId,
		filter: "startswith",
		dataValueField: "id",
		change: function(e){
			let val = e.sender.value();
			createNewItemIfNeed(day, lessonId, classId, id);
				
			if(val){
				timetable_hash[day][lessonId][classId][id]['room_id'] = val;
				e.sender.input.parent().removeClass("back-error");
			} else {
				timetable_hash[day][lessonId][classId][id]['room_id'] = null;
			}
		}
	});
	
	$it.kendoComboBox({
		cascadeFrom: "is_" + id,
		cascadeFromField: "subject_id",
		dataSource: teachers,
		dataTextField: "name",
		clearButton: false,
		filter: "startswith",
		value: teacherId,
		dataValueField: "id",
		autoWidth: true,
		change: function(e){	
			let val = e.sender.value();
			createNewItemIfNeed(day, lessonId, classId, id);
						
			if(val){
				timetable_hash[day][lessonId][classId][id]['teacher_id'] = val;
				e.sender.input.parent().removeClass("back-error");	
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
	
	$ic = $("#ic_" + id)
	$ic.change(function(){
		createNewItemIfNeed(day, lessonId, classId, id);
		timetable_hash[day][lessonId][classId][id]['comment'] = this.value;
	})
	if(comment && (comment.length > 0)){
		$ic.parent().show();
		$ic.val(comment);
	}

	let f = parseInt(flags);
	
	if(f > 0){
		let block = $subj.parents(".subject-block");
		block.addClass(config.flagsClass);
		block.find(".flags-block").show();
		
		if((f & flagList.online) == flagList.online)
			block.find(".f-online").show();
			
		if((f & flagList.optional) == flagList.optional)
			block.find(".f-optional").show();		
			
		if((f & flagList.olimp) == flagList.olimp)
			block.find(".f-olimp").show();
		
		if((f & flagList.advice) == flagList.advice)
			block.find(".f-advice").show();
		
		if((f & flagList.ege) == flagList.ege)
			block.find(".f-ege").show();	

		if((f & flagList.extra) == flagList.extra)
			block.find(".f-extra").show();			
	} 
	
}

function paint(type){
	if(!type){
		type = "chess";
	}
	
	$t = $("table.time-t");
	$t.removeClass("colors-by-class colors-by-week colors-chess");
	switch(type){
		case 'class': $t.addClass("colors-by-class"); break;
		case 'week': $t.addClass("colors-by-week"); break;
		case 'chess': $t.addClass("colors-by-week colors-chess"); break;
	}
	localStorage.setItem("color_type", type);
}

function addOrRemoveComment(block){
	const $td = block.parent();
	const classId = $td.data("class-id");
	const lessonId = $td.data("lesson-id");
	const day = $td.data("day");
	const id = block.data("item-id");
	const $ic = $("#ic_" + id);
	const $icp = $ic.parent();
	if($icp.is(":visible")){
		$icp.hide();
		$ic.val("");
		if(timetable_hash[day] && timetable_hash[day][lessonId] && timetable_hash[day][lessonId][classId] && timetable_hash[day][lessonId][classId][id]){
			timetable_hash[day][lessonId][classId][id]['comment'] = null;
		}
	} else {
		$icp.show();
	}
}

function addOrRemoveFlag(block, flag){
	const $td = block.parent();
	const classId = $td.data("class-id");
	const lessonId = $td.data("lesson-id");
	const day = $td.data("day");
	const id = block.data("item-id");
	createNewItemIfNeed(day, lessonId, classId, id);
	let obj = timetable_hash[day][lessonId][classId][id];
	obj['flags'] = parseInt(obj['flags']) ^ flag;
	
	if(obj['flags'] > 0){
		block.addClass(config.flagsClass);
		block.find(".flags-block").show();
		
		if((obj['flags'] & flagList.online) == flagList.online)
			block.find(".f-online").show();
		else
			block.find(".f-online").hide();
			
		if((obj['flags'] & flagList.optional) == flagList.optional)
			block.find(".f-optional").show();
		else
			block.find(".f-optional").hide();
			
		if((obj['flags'] & flagList.olimp) == flagList.olimp)
			block.find(".f-olimp").show();
        else
			block.find(".f-olimp").hide();
			
		if((obj['flags'] & flagList.advice) == flagList.advice)
			block.find(".f-advice").show();
        else
			block.find(".f-advice").hide();
			
		if((obj['flags'] & flagList.ege) == flagList.ege)
			block.find(".f-ege").show();
        else
			block.find(".f-ege").hide();
			
		if((obj['flags'] & flagList.extra) == flagList.extra)
			block.find(".f-extra").show();
        else
			block.find(".f-extra").hide();
	} else {
		block.removeClass(config.flagsClass);
		block.find(".flags-block").hide();
	}
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
	initItem(defaultId, null, null, null, classId, lessonId, day, 0, null);	
}

function deleteSubject($block){
	let delId = $block.data("item-id");
	let newArr = [];
	for(let i=0; i < timetable.length; i++){
		if(timetable[i]['id'] != delId){
			newArr.push(timetable[i]);
		}
	}
	timetable = newArr;
	$block.remove()
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
	
	let fromClassId = $from.data("class-id");
	
	$from.find(".subject-block").each(function(){
		let defaultId = getDefaultId();
		let flags = 0;
		let sid = this.getAttribute("data-item-id");
		if(timetable_hash[day] && timetable_hash[day][lessonId] && timetable_hash[day][lessonId][fromClassId] && timetable_hash[day][lessonId][fromClassId][sid]){
			let newObj = Object.assign({}, timetable_hash[day][lessonId][fromClassId][sid]);
			flags = newObj['flags'];
			newObj['id'] = defaultId;
			newObj['class_id'] = classId;
			timetable.push(newObj);
			if(!timetable_hash[day][lessonId][classId])
				timetable_hash[day][lessonId][classId] = {};
			timetable_hash[day][lessonId][classId][defaultId] = newObj;
		}
		
		$td.append(tpl.replaceAll("{no}",defaultId));
		initItem(defaultId, null, null, null, classId, lessonId, day, flags, $("#ic_" + sid).val());		
		
		$("#is_" + defaultId).data("kendoComboBox").value($("#is_" + sid).data("kendoComboBox").value());
		$("#ir_" + defaultId).data("kendoComboBox").value($("#ir_" + sid).data("kendoComboBox").value());
		$("#it_" + defaultId).data("kendoComboBox").value($("#it_" + sid).data("kendoComboBox").value());
		
	});	
}

function initContextMenu(){
	$("#menu").kendoContextMenu({
		target: "table",
		filter: "div.subject-block",
		animation: {
			open: { effects: "fadeIn" },
			duration: 500
		},
		select: function(e, t) {
			console.log(e)
			switch(e.item.getAttribute("data-command")){
				case 'add': addSubject($(e.target).parent()); break;
				case 'copy:left': copy($(e.target).parent()); break;
				case 'copy:right': copy($(e.target).parent(), true); break;
				case 'comment': addOrRemoveComment($(e.target)); break;
				case 'mark:online': addOrRemoveFlag($(e.target), flagList.online); break;
				case 'mark:optional': addOrRemoveFlag($(e.target), flagList.optional); break;
				case 'mark:olimp': addOrRemoveFlag($(e.target), flagList.olimp); break;
				case 'mark:advice': addOrRemoveFlag($(e.target), flagList.advice); break;
				case 'mark:ege': addOrRemoveFlag($(e.target), flagList.ege); break;
				case 'mark:extra': addOrRemoveFlag($(e.target), flagList.extra); break;
				case 'delete': deleteSubject($(e.target)); break;
			}
		}
	});
	$("#menutd").kendoContextMenu({
		target: "table",
		filter: "td.subject",
		animation: {
			open: { effects: "fadeIn" },
			duration: 500
		},
		select: function(e, t) {
			console.log(e)
			switch(e.item.getAttribute("data-command")){
				case 'add': addSubject($(e.target)); break;
				case 'copy:left': copy($(e.target)); break;
				case 'copy:right': copy($(e.target), true); break;				
			}
		}
	});
}

hashCode = function(s){
  return s.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);              
}

setInterval(function(){
	var dt = JSON.stringify(timetable);

	if(window.timetable_hash == dt){
		return;
	}
	
	window.timetable_hash = dt;
	
	$.post( "editor-auto-save.php", {dataauto: dt})
	  .done(function(ev, a, b) {
		var r = JSON.parse(ev);
		note.show({message: r.message}, r.status);
	  })
	  .fail(function() {
		alert( "error" );
	  })
}, config.autosavePeriodInMinutes * 60 * 1000);





