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
	"link":null,
	"code":null,
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
	
	$("td.subject").each(function(){
		const $el = $(this);
		const classId = $el.data("class-id");
		const lessonId = $el.data("lesson-id");
		const day = $el.data("day");
		
		if(timetable_hash[day] && timetable_hash[day][lessonId] && timetable_hash[day][lessonId][classId]){
			for(let id in timetable_hash[day][lessonId][classId]){
				const tt = timetable_hash[day][lessonId][classId][id];
				$el.append(tpl.replaceAll("{no}",id));
				initItem(id, tt.subject_id, tt.teacher_id, tt.room_id, classId, lessonId, day, tt.flags, tt.comment, tt.link, tt.code);
			}
		} else {
			let defaultId = getDefaultId();
			$el.append(tpl.replaceAll("{no}",defaultId));
			initItem(defaultId, null, null, null, classId, lessonId, day, 0, null, null, null);				
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

function initItem(id, subjectId, teacherId, roomId, classId, lessonId, day, flags, comment, link, code){

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
				let key2 = val + '-' + classId + '--';
				let key1 = val + '-' + classId + '-' + lessonId + '-';
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
	
	$il = $("#il_" + id);
	$id = $("#id_" + id);
	$il.change(function(){
		createNewItemIfNeed(day, lessonId, classId, id);
		timetable_hash[day][lessonId][classId][id]['link'] = this.value;
	}).contextmenu(function(e){e.stopPropagation();});
	if(link && (link.length > 0)){
		$id.parent().show();
		$il.val(link);
	}
	
	
	$id.change(function(){
		createNewItemIfNeed(day, lessonId, classId, id);
		timetable_hash[day][lessonId][classId][id]['code'] = this.value;
	}).contextmenu(function(e){e.stopPropagation();});
	if(code && (code.length > 0)){
		$id.parent().show();
		$id.val(code);
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
var dragged;
function initDragAndDrop(){
	

  document.addEventListener("drag", function( event ) {

  }, false);

  document.addEventListener("dragstart", function( event ) {
      dragged = event.target;
  }, false);

  document.addEventListener("dragend", function( event ) {

  }, false);
  
    document.addEventListener("dragover", function( event ) {
      // prevent default to allow drop
      event.preventDefault();
  }, false);

  document.addEventListener("dragenter", function( event ) {
      if ( event.target.className == "dropzone" ) {
          event.target.style.background = "purple";
      }

  }, false);

  document.addEventListener("dragleave", function( event ) {
      // reset background of potential drop target when the draggable element leaves it
      if ( event.target.className == "dropzone" ) {
          event.target.style.background = "";
      }

  }, false);

  document.addEventListener("drop", function( event ) {
      event.preventDefault();
      // move dragged elem to the selected drop target
	  $td = $(event.target);
	  if(!$td.is("td")){
		  $td = $(event.target).parents("td");
	  }
	  
	  
	  const d = $(dragged);
	  
	  if($td.prop('id') == d.parent().prop('id')){
		  console.log("skip");
		  return;
	  }
	  
	  const dd = d.parent().data();
	  
      if ( $td.hasClass("dropzone")){		  
			
			const classId = $td.data("class-id");
			const lessonId = $td.data("lesson-id");
			const day = $td.data("day");
			const id = getDefaultId();
			const tpl = $("#tpl").text();
			createNewItemIfNeed(day, lessonId, classId, id);			
			$td.append(tpl.replaceAll("{no}",id));

			
			if(	timetable_hash[dd['day']] && 
				timetable_hash[dd['day']][dd['lessonId']] && 
				timetable_hash[dd['day']][dd['lessonId']][dd['classId']] && 
				timetable_hash[dd['day']][dd['lessonId']][dd['classId']][dragged.dataset.itemId]){
					const tt = timetable_hash[dd['day']][dd['lessonId']][dd['classId']][dragged.dataset.itemId];
					let nn = timetable_hash[day][lessonId][classId][id];
					nn.subject_id = tt.subject_id;
					nn.teacher_id = tt.teacher_id;
					nn.room_id = tt.room_id;
					nn.flags = tt.flags;
					nn.comment = tt.comment;
					nn.link = tt.link;
					nn.code = tt.code;
					initItem(id, tt.subject_id, tt.teacher_id, tt.room_id, classId, lessonId, day, tt.flags, tt.comment, tt.link, tt.code);
			} else {
				initItem(id, null, null, null, classId, lessonId, day, 0, null, null, null);
			}
			
			dragged.parentNode.removeChild( dragged );
			deleteSubject(d);
      }
    
  }, false);
}

function addOrRemoveLink(block){
	const $td = block.parent();
	const classId = $td.data("class-id");
	const lessonId = $td.data("lesson-id");
	const day = $td.data("day");
	const id = block.data("item-id");
	const $il = $("#il_" + id);
	const $id = $("#id_" + id);
	const $icp = $id.parent();
	if($icp.is(":visible")){
		$icp.hide();
		$il.val("");
		$id.val("");
		if(timetable_hash[day] && timetable_hash[day][lessonId] && timetable_hash[day][lessonId][classId] && timetable_hash[day][lessonId][classId][id]){
			timetable_hash[day][lessonId][classId][id]['link'] = null;
			timetable_hash[day][lessonId][classId][id]['code'] = null;
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
	initItem(defaultId, null, null, null, classId, lessonId, day, 0, null, null, null);	
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

function copy($td, direction){
	let $from;
	if(direction == 1)
		$from = $td.parent().prev().find("td:eq("+$td.index()+")");
	else if(direction == 2)
		$from = $td.next();
	else if(direction == 3)
		$from = $td.parent().next().find("td:eq("+$td.index()+")");
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
		initItem(defaultId, null, null, null, classId, lessonId, day, flags, $("#ic_" + sid).val(), $("#il_" + sid).val(), $("#id_" + sid).val());		
		
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
			switch(e.item.getAttribute("data-command")){
				case 'add': addSubject($(e.target).parent()); break;
				case 'copy:left':  copy($(e.target).parent(), 4); break;
				case 'copy:right': copy($(e.target).parent(), 2); break;
				case 'copy:top':  copy($(e.target).parent(), 1); break;
				case 'copy:bottom': copy($(e.target).parent(), 3); break;
				case 'comment': addOrRemoveComment($(e.target)); break;
				case 'link': addOrRemoveLink($(e.target)); break;
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
			//console.log(e)
			switch(e.item.getAttribute("data-command")){
				case 'add': addSubject($(e.target)); break;
				case 'copy:left':  copy($(e.target), 4); break;
				case 'copy:right': copy($(e.target), 2); break;
				case 'copy:top':  copy($(e.target), 1); break;
				case 'copy:bottom': copy($(e.target), 3); break;				
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


function clear(type, param){
	if(type == 'all'){
		for(let i=0; i< timetable.length; i++){
			clearItem(timetable[i]);
		}
	} else if(type == 'byday') {
		let dt = $("#day_num_" + param).data("date");
		for(let i=0; i< timetable.length; i++){
			if(timetable[i]['day'] == dt)
				clearItem(timetable[i]);
		}
	} else if(type == 'byclass') {
		for(let i=0; i< timetable.length; i++){
			if(timetable[i]['class_id'] == param)
				clearItem(timetable[i]);
		}
	} else if(type == 'online'){
		for(let i=0; i< timetable.length; i++){
			let item = timetable[i];
			item['flags'] = parseInt(item['flags']) & 1022;
			let block = $("#ir_" + item['id']).parents(".subject-block");
			if (item['flags'] == 0)
				block.find(".flags-block").hide();
			else
				block.find(".f-online").hide();
		}
	}
}

function clearItem(item){
	item['room_id'] = null; 
	item['subject_id'] = null; 
	item['teacher_id'] = null; 
	item['flags'] = 0;
	$("#ir_" + item['id']).data("kendoComboBox").value(null);
	$("#it_" + item['id']).data("kendoComboBox").value(null);
	$("#is_" + item['id']).data("kendoComboBox").value(null);
	
	let block = $("#ir_" + item['id']).parents(".subject-block");
	block.removeClass(config.flagsClass);
	block.find(".flags-block").hide();
}

function setOnline(type, param){
	if(type == 'all'){
		for(let i=0; i< timetable.length; i++){
			setOnlineItem(timetable[i]);
		}
	} else if(type == 'byday') {
		let dt = $("#day_num_" + param).data("date");
		for(let i=0; i< timetable.length; i++){
			if(timetable[i]['day'] == dt)
				setOnlineItem(timetable[i]);
		}
	} else if(type == 'byclass') {
		for(let i=0; i< timetable.length; i++){
			if(timetable[i]['class_id'] == param)
				setOnlineItem(timetable[i]);
		}
	}
}

function setOnlineItem(item, param){
	let fint = parseInt(item['flags']);
	
	if((fint & flagList.online) == flagList.online){
		return;
	}
	
	let f = (fint | flagList.online);
	item['flags'] = f;
	let block = $("#ir_" + item['id']).parents(".subject-block");
	block.addClass(config.flagsClass);
	block.find(".flags-block").show();	
	block.find(".f-online").show();
}

function checkTeachers(isChane, str){
	if(!str){
		str = '';
	}
	
	$(".back-error").removeClass("back-error");
	let ret = [];
	let check = {};
	for(let i=0; i< timetable.length; i++){
		let el = timetable[i];
		if(el['subject_id'] && !el['teacher_id']){
			let d = new Date(el['day']);
			ret.push($("#is_" + el['id']).parent().find("input").val() + " ("+ d.getDayOfWeek() + ", " + el['lesson_id'] + ' ????????, ' + class_hash[el['class_id']] + ")");
			$("#it_" + el['id']).prev().addClass("back-error");
		}
		
		if(el['teacher_id']){
			let k = el['day'] + "|" + el['lesson_id'] + "|" + el['teacher_id'];
			if(check.hasOwnProperty(k)){
				check[k].push(el);
			} else {
				check[k] = [el];					
			}
		}
	}
	
	let ret2 = [];
	for(let i in check){
		if(check[i].length > 1){
			let baseroom = check[i][0]['room_id'];
			for(let j=1; j < check[i].length; j++){
				let el = check[i][j];
				if(baseroom != check[i][j]['room_id']){
					let rtmp = [];
					for(let k=0; k < check[i].length; k++){
						rtmp.push(check[i][k]['room_id'] ? check[i][k]['room_id'] : '??/??');
						$("#ir_" + check[i][k]['id']).prev().addClass("back-error");
					}
					let d = new Date(el['day']);
					ret2.push($("#it_" + el['id']).parent().find("input").val() + " ("+ d.getDayOfWeek() + ", " + el['lesson_id'] + ' ????????, ??????????????????: ' + rtmp.join(", ") + ")");
					break;
				}
			}
		}
	}
	
	if(ret.length > 0){
		str = str + '?????? ?????????????????? ?????????????? ???? ???????????? ??????????????????????????:\n  ' + ret.join("\n  ") + "\n\n";
	}
	
	if(ret2.length > 0){
		str = str + '?????? ?????????????????? ???????????????????????????? ?????????????? ???????????? ?????????????????? ?? ???????????? ?????????? ????????:\n  ' + ret2.join("\n  ") + "\n\n";
	}
	
	if(isChane){				
		return str;
	}
	
	if(ret.length > 0 || ret2.length > 0){
		alert(str);
	} else {
		alert("?????? ?????????????????????????? ?????????????????? ??????????????????");
	}
}

function checkRooms(isChane, str){
	if(!str){
		str = '';
	}
	
	if(!isChane){
		$(".back-error").removeClass("back-error");
	}
	let ret = [];
	let check = {};
	for(let i=0; i< timetable.length; i++){
		let el = timetable[i];
		if(el['subject_id'] && !el['room_id'] && el['subject_id'] != '8'){
			let d = new Date(el['day']);
			ret.push($("#is_" + el['id']).parent().find("input").val() + " ("+ d.getDayOfWeek() + ", " + el['lesson_id'] + ' ????????, ' + class_hash[el['class_id']] + ")");
			$("#ir_" + el['id']).prev().addClass("back-error");
		}
		
		if(el['room_id']){
			let k = el['day'] + "|" + el['lesson_id'] + "|" + el['room_id'];
			if(check.hasOwnProperty(k)){
				check[k].push(el);
			} else {
				check[k] = [el];					
			}
		}
	}
	
	if(ret.length > 0){
		str = str + '?????? ?????????????????? ?????????????? ???? ?????????????? ??????????????????:\n  ' + ret.join("\n  ") + "\n\n";
	}
	
	let ret2 = [];
	for(let k in check){
		if(check[k].length > 1){
		let hash = check[k][0]['subject_id']+'|'+check[k][0]['teacher_id']+'|'+ check[k][0]['flags'];
			for(let i = 1; i< check[k].length; i++){
				let hash2 = check[k][i]['subject_id']+'|'+check[k][i]['teacher_id']+'|'+ check[k][i]['flags'];
				if(hash2 != hash){
					let d = new Date(check[k][0]['day']);
					ret2.push(check[k][0]['room_id'] + " ("+ d.getDayOfWeek() + ", " + check[k][0]['lesson_id'] + ' ????????)');
					for(let j = 0; j< check[k].length; j++){
						$("#ir_" + check[k][j]['id']).prev().addClass("back-error");
					}
					break;
				}
			}
		}
	}
	
	if(ret2.length > 0){
		str = str + '?????? ?????????????????? ?????????????????? ?????????????? ??????????:\n  ' + ret2.join("\n  ") + "\n\n";
	}
	
	
	if(isChane){				
		return str;
	}
	
	if(ret.length > 0 || ret2.length > 0){
		alert(str);
	} else {
		alert("?????? ?????????????????? ?????????????????? ??????????????????");
	}
}




