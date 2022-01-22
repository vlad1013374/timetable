

$(".teacher-row").click(function() {
	const tpl = $("#tpl").text();
	const tpl_sub = $("#tpl-sub").text();
	var id = $(this).children('.teacher').attr('data-id');
	var name = $(this).children('.teacher').text();
	var content = "";
	var subname = "";
	tsub =[]
	$(this).children().children('.t-sub').each(function () {
		
		let sub_id = $(this).attr("data-id")
		let sub_name = $(this).text()
		tsub.push({id:sub_id, name:sub_name})	
	})


	$("#offcanvaseditteacher").children(".offcanvas-body").remove()
	$('#offcanvaseditteacher').append(tpl.replaceAll("{id}",id).replaceAll("{name}",name)); 
	for (var i = 0 ; i < tsub.length; i++) {
		$(".content-add-teacher").append(tpl_sub.replaceAll("{subName}", tsub[i].name).replaceAll("{subId}",tsub[i].id));
	}

	

	$(".add-sub-input").click(function() {
		$(".content-add-teacher").append(tpl_sub.replaceAll("{subName}", "").replaceAll("{subId}",""))
	})
})

$(".add-t-sub-select").click(function() {
	var clonedNode = document.getElementById("t-sub-select").cloneNode(true);
 	
	document.querySelector(".t-subs").appendChild(clonedNode);
})