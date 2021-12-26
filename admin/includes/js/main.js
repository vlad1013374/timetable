$(document).ready(function(){
	$('.js-selectize').selectize();
});

$(".but").click(function() {
		var week =  $("input[name='week']:checked").val();
		var get_class =  $("input[name='class']:checked").val();
		location.href = "//rasp2/admin?week=" + week ;
		console.log(week);
});