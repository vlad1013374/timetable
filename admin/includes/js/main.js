$("ul li").each(function () {
	if($(this).attr("data-active") == 0){
		$(this).append("<button class='but-act'>Активировать</button>");
	}else{
		$(this).append("<label class='is-active'>(активная)</label>");
	}
});


$(".but-act").click(function(){
	var value = $(this).parent().attr("data-week");
	$.post("admin.php", {weekActive: value})
	  .done(function(ev) {
	 
		$("ul li").each(function() {
			if($(this).attr("data-week") == value){
				$(this).find("button").remove();
				$(this).append("<label class='is-active'>(активная)</label>");
				$(this).attr("data-active", "1");
			}else if($(this).attr("data-active") == 1){
				$(this).find(".is-active").remove();
				$(this).append("<button class='but-act'>Активировать</button>");
				$(this).attr("data-active", "0");
			}
		})
	  })
	  .fail(function() {
		alert( "error" );
	  })
})




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