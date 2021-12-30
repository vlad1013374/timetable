

$(".add-week").click(function () {
	var number =   $(".week-num").val();
	var week_start =   $(".week-start").val();
	var week_stop =  $(".week-stop").val();
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