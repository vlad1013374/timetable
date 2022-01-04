$("ul li").each(function () {
	if($(this).attr("data-active") == 0){
		$(this).append("<button class='but-act'>Активировать</button>");
	}else{
		$(this).append("<label class='is-active'>(активная)</label>");
	}
});

$("#listView").on("click", ".delete-week", function(){
	var week_deleted_id = $(this).parent().attr("week-id");
	$.post("admin.php", {weekDelete: week_deleted_id})
	  .done(function(ev) {
		document.location.reload();
	  })
	  .fail(function() {
		alert( "error" );
	  })
})
$(".edit-week").click(function () {
	var number = $(".k-formatted-value.week-num.edit.k-input").attr("aria-valuenow");
	var week_start = $(".week-start.edit.k-input").val();
	var week_stop = $(".week-stop.edit.k-input").val();
	var number_copy = $("select.copy.edit").data("kendoDropDownList").value();
	

	var pieces = week_start.split('.');
	pieces.reverse();
	var week_start = pieces.join('-');
	var pieces = week_stop.split('.');
	pieces.reverse();
	var week_stop = pieces.join('-');
	

	
	let obj = {number: number, start : week_start , stop : week_stop, copy:number_copy, comment:$(".week-comment.edit").val()};
	var dt = JSON.stringify(obj);
	$.post("admin.php", {weekEdit: dt})
	  .done(function(ev) {
		document.location.reload();
	  })
	  .fail(function() {
		alert( "error" );
	  })
	
})

$("#listView").on("click", ".but-act", function(){
	var value = $(this).data("id");
	$.post("admin.php", {weekActive: value})
	  .done(function(ev) {
	 
		document.location.reload();
	  })
	  .fail(function() {
		alert( "error" );
	  })
})




$(".add-week").click(function () {
	var number = $(".k-formatted-value.week-num.k-input").attr("aria-valuenow");
	var week_start = $(".week-start.k-input").val();
	var week_stop = $(".week-stop.k-input").val();
	var number_copy = $("select.copy").data("kendoDropDownList").value();

	var pieces = week_start.split('.');
	pieces.reverse();
	var week_start = pieces.join('-');
	var pieces = week_stop.split('.');
	pieces.reverse();
	var week_stop = pieces.join('-');
	

	
	let obj = {number: number, start : week_start , stop : week_stop, copy:number_copy, comment:$(".week-comment").val()};
	var dt = JSON.stringify(obj);
	$.post("admin.php", {week: dt})
	  .done(function(ev) {
		document.location.reload();
	  })
	  .fail(function() {
		alert( "error" );
	  })
	
})


$(".week-start, .week-stop").kendoDatePicker();
$(".week-num").kendoNumericTextBox({
	min:1,
	format:"n0"
});
$(".copy").kendoDropDownList({autoWidth: true});