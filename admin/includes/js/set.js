

$("#teachers-table").on("click",".teacher-row",function() {

	const tpl = $("#tpl").text();
	const tpl_sub = $("#tpl-sub").text();

	let id = $(this).attr('data-id');
	let name = $('#teachers-table').DataTable().row( this ).data().name_teach
	let subjects_ids = $('#teachers-table').DataTable().row( this ).data().sub_id
	let subjects = $('#teachers-table').DataTable().row( this ).data().name_sub
	let re = /\s*,\s*/;
	let sub_ids = subjects_ids.split(re);
	let subs = subjects.split(re);
	
	let tsub =[]

	for (let i = 0; i < subs.length; i++) {
		let id_sub = sub_ids[i]
		let name_sub = subs[i]
		tsub.push({id:id_sub, name:name_sub})
	}	

	$("#offcanvaseditteacher").children(".offcanvas-body").remove()
	$('#offcanvaseditteacher').append(tpl.replaceAll("{name}",name)); 
	for (let i = 0 ; i < tsub.length; i++) {
		$("select[name='sub-edit-teacher']").append(tpl_sub.replaceAll("{subName}", tsub[i].name).replaceAll("{subId}",tsub[i].id));
	}

	let t_sub_select = $("#t-sub-select").kendoMultiSelect().data("kendoMultiSelect");
	
	$("#edit-teach-save").click(function () {
		let subjects = t_sub_select.value()
		
		let dt = {id:id, name: name, subjects: subjects}
		$.post("data-set-control.php", {editTeacher: dt} )
			.done(function () {
				$('#teachers-table').DataTable().ajax.reload()
				$(".btn-close").click()
			})
		
	})
	
	

})

$(".add-subject-block").on("click", "#add-subject", function () {
	let name = $("#new-subject-name").val()
	let short_name = $("#new-subject-short-name").val()
	let default_audithory = $("#new-default-auditory").val()

	let dt = {name: name, short_name : short_name, aud : default_audithory}

	$.post("data-set-control.php", {newSubject:dt})
		.done(function (ev) {
			$('#subjects-table').DataTable().ajax.reload()
			$(".btn-close").click()
		})
})


$(".add-room-block").on("click", "#add-room", function () {
	let number = $("#number-new-room").val()
	let capacity = $("#capacity").val()

	let dt = {number: number, capacity:capacity}

	$.post("data-set-control.php", {newRoom:dt})
		.done(function (ev) {
			$('#rooms-table').DataTable().ajax.reload()
			$(".btn-close").click()
		})
})

$(".add-teacher-block").on("click", "#add-teacher",function () {
	let name = $("#new-teacher-name").val()
	let subjectsIDs = $("select[name='sub-add-teacher']").val()
	let dt = {name: name, subjects : subjectsIDs}
	$.post("data-set-control.php", {newTeacher:dt})
		.done(function (ev) {
			$('#teachers-table').DataTable().ajax.reload()
			$(".btn-close").click()
		})
})

$(".add-links-block").on("click", "#add-link", function () {
	let teacher = $("#links-teacher-select").val();
	let subject = $("#links-subject-select").val();
	let classID = $("#links-class-select").val();
	let lesson = $("#links-lesson-select").val() ;
	let weekDay = $("#links-week-day-select").val();
	let dt = {teacher: teacher[0], subject:subject[0], class: classID[0], lesson : lesson[0], weekDay: weekDay[0]};
	$.post("data-set-control.php", {newLink:dt})
		.done(function (ev) {
			$('#links-table').DataTable().ajax.reload()
			$(".btn-close").click()

		})

})

$("#links-table").on("click", "#delete-link", function () {
	let teacher = $(this).parents("tr").attr("data-t-id");
	let subject = $(this).parents("tr").attr("data-s-id");
	let classID = $(this).parents("tr").attr("data-c-id");
	let dt = {teacher: teacher, subject: subject, class: classID};
	console.log(dt);
	if(!confirm("Удалить связку?")){
		return;
	}
    $.post("data-set-control.php", {removeLink:dt})
		.done(function (ev) {
			$('#links-table').DataTable().ajax.reload()
		})
            
        
        
	
})


