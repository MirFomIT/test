
$( function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy.mm.dd' });
    // $("#datepicker").datepicker( "setDate" , "2019-01-20" );
} );

var countChecked = function() {
    $('#activity-card').css('disabled','enabled');
};
$('#activity-card').css('disabled','disabled');

$( "input[type=checkbox]" ).on( "click", countChecked );



