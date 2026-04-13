var BASE_UNIT = 10000;
var BASE_RATE = 1.5;
var CHECK_UNIT = 100000;
var CHECK_YEAR = 1000;
var OVER_UNIT_MSG = '口数は' + CHECK_UNIT + '未満で指定してください。';
var OVER_YEAR_MSG = '年は' + CHECK_YEAR + '未満で指定してください。';

$(function () {
    
    $('#unit').on('keyup', function(){
        calcSimulation(true);
        
    });
    
    $('#year').on('keyup', function(){
        checkYear($(this).val());
        
    });
    
    $('#calc').on('click', function(){
        calcSimulation();
        
    });
    
    $('#reset').on('click', function(){
        reset();
        
    });
    
});

function reset() {
    $('#unit').val('');
    $('#year').val('');
    $('#unit_text').text(0);
    $('#result_text').text(0);
    
    return;
}

function checkYear(year) {
    if (year >= CHECK_YEAR) {
        $('#unit_text').text(0);
        $('#result_text').text(0);
        alert(OVER_YEAR_MSG);
        return false;
        
    }//if
    
    return true;
    
}

function calcSimulation(unitOnlyFlg) {
    
    var unit = $('#unit').val();
    var year = $('#year').val();
    
    if (!$.isNumeric(unit)) {
        $('#unit_text').text(0);
        $('#result_text').text(0);
        return;
        
    }//if
    
    if (unit >= CHECK_UNIT) {
        $('#unit_text').text(0);
        $('#result_text').text(0);
        alert(OVER_UNIT_MSG);
        return;
        
    }//if
    
    if (!checkYear(year)) {
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