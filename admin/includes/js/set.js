

$("#teachers-table").on("click",".teacher-row",function() {
	

	const tpl = $("#tpl").text();
	const tpl_sub = $("#tpl-sub").text();

	var id = $(this).attr('data-id');
	var name = $('#teachers-table').DataTable().row( this ).data().name_teach
	var subjects_ids = $('#teachers-table').DataTable().row( this ).data().sub_id
	var subjects = $('#teachers-table').DataTable().row( this ).data().name_sub
	var re = /\s*,\s*/;
	var sub_ids = subjects_ids.split(re);
	var subs = subjects.split(re);
	console.log(subs, sub_ids)
	var tsub =[]

	for (var i = 0; i < subs.length; i++) {
		let id_sub = sub_ids[i]
		let name_sub = subs[i]
		tsub.push({id:id_sub, name:name_sub})
	}	

	$("#offcanvaseditteacher").children(".offcanvas-body").remove()
	$('#offcanvaseditteacher').append(tpl.replaceAll("{id}",id).replaceAll("{name}",name)); 
	for (var i = 0 ; i < tsub.length; i++) {
		$(".content-add-teacher").append(tpl_sub.replaceAll("{subName}", tsub[i].name).replaceAll("{subId}",tsub[i].id).replaceAll("{type}", "edit"));
	}

	

	$(".add-sub-input").click(function() {
		$(".content-add-teacher").append(tpl_sub.replaceAll("{subName}", "").replaceAll("{subId}","").replaceAll("{type}", "edit"))
		$(".delete").click(function() {
			$(this).parent().remove();
		})
	})
	
	$("#edit-teach-save").click(function () {
		let subjectsID = []
		$("select[name='sub-edit-teacher']").each(function () {
			subjectsID.push($(this).val())
		})
		let dt = {id:id, name: name, subjects: subjectsID}
		$.post("data-set-control.php", {editTeacher: dt} )
			.done(function () {
				$('#teachers-table').DataTable().ajax.reload()
				$(".btn-close").click()
			})
	
	})
	$(".delete").click(function() {
		$(this).parent().remove();
	})

})

$(".add-t-sub-select").click(function() {
	const tpl = $("#tpl").text();
	const tpl_sub = $("#tpl-sub").text();
	$(".t-subs").append(tpl_sub.replaceAll("{subName}", "").replaceAll("{subId}","").replaceAll("{type}", "add"))
	$(".delete").click(function() {
		$(this).parent().remove();
	})

})

$(".add-teacher-block").on("click", "#add-teacher",function () {
	let name = $("#new-teacher-name").val()
	let subjects = $("select[name='sub-add-teacher']").val()
	let dt = {name: name, subjects : subjects}
	$.post("data-set-control.php", {newTeacher:dt})
		.done(function (ev) {
			$('#teachers-table').DataTable().ajax.reload()
			$(".btn-close").click()
		})
})


