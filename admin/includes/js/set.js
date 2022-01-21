

$(".teacher-row").click(function() {
	var id = $(this).children('.teacher').attr('data-id');
	var name = $(this).children('.teacher').text();
	var content = "";
	tsub =[]
	$(this).children().children('.t-sub').each(function () {
		tsub.push($(this).text())
	})

	function sub(tsub) {
		if(tsub.length>1){
			for (var i = 0 ; i < tsub.length; i++) {
				content = content+'<input type="text" value="'+tsub[i]+'">'
			}
			
		}else{
			content = '<input type="text" value = "'+tsub[0]+'">'
		}
	}
	sub(tsub)
	
	const tpl = $("#tpl").text();
	$("#offcanvaseditteacher").children(".offcanvas-body").remove()
	$('#offcanvaseditteacher').append(tpl.replaceAll("{id}",id).replaceAll("{name}",name).replaceAll("{somecode}", content)); 

	$(".add-sub-input").click(function() {
		$(".content-add-teacher").append('<input type="text" value="">')
	})
})

$(".add-t-sub-select").click(function() {
	var clonedNode = document.getElementById("t-sub-select").cloneNode(true);
 
	document.querySelector(".t-subs").appendChild(clonedNode);
})