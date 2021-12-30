



$(".add-week").click(function () {

	var week_start = $(".week-start.k-input").val();
	var week_stop = $(".week-stop.k-input").val();
	
	var pieces = week_start.split('.');
	pieces.reverse();
	var week_start = pieces.join('-');
	var pieces = week_stop.split('.');
	pieces.reverse();
	var week_stop = pieces.join('-');
	

	var number = $(".k-formatted-value.week-num.k-input").attr("aria-valuenow");
	let obj = {number: number, start : week_start , stop : week_stop};
	var dt = JSON.stringify(obj);
	$.post("admin.php", {week: dt})
	  .done(function(ev) {
		alert(ev);
		
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