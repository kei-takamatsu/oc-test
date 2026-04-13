

var BASE_UNIT = 10000;
var BASE_RATE = 1.5;


$(function () {
	
	$('#unit').on('keyup', function(){
		calcSimulation(true);
		
	});
	
	$('#calc').on('click', function(){
		calcSimulation();
		
	});
	
});

function calcSimulation (unitOnlyFlg) {
	
	var unit = $('#unit').val();
	var year = $('#year').val();
	
	if (!$.isNumeric(unit)) {
		$('#unit_text').text(0);
		$('#result_text').text(0);
		return;
		
	}//if
	
	unitResult = Math.floor(BASE_UNIT * unit);
	$('#unit_text').text(unitResult.toLocaleString());
	
	if (unitOnlyFlg) {
		return;
		
	}//if
	
	if (!$.isNumeric(unit)) {
		$('#result_text').text(0);
		return;
		
	}//if
	
	result = Math.floor(unitResult * (BASE_RATE / 100) * year);
	$('#result_text').text(result.toLocaleString());
	
}






