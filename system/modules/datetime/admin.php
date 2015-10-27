<link rel="stylesheet" type="text/css" href="../system/modules/<?php echo $val; ?>/css/bootstrap-datetimepicker.min.css"></link>
<script type="text/javascript" src="../system/modules/<?php echo $val; ?>/js/moment.min.js"></script>
<script type="text/javascript" src="../system/modules/<?php echo $val; ?>/js/bootstrap-datetimepicker.min.js"></script>


<div class="form-group">
    <div class='input-append input-group date' id='datetimepicker'>
        <input type="text" class="form-control" data-format="dd/MM/yyyy hh:mm:ss"></input>
        <span class="input-group-addon add-on">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<script type="text/javascript">
$(function() {
	$('#datetimepicker').datetimepicker({
	    pickDate: true,                 //en/disables the date picker
	    pickTime: true,                 //en/disables the time picker
	    useMinutes: true,               //en/disables the minutes picker
	    useSeconds: true,               //en/disables the seconds picker
	    useCurrent: true,               //when true, picker will set the value to the current date/time
	    minuteStepping:1,               //set the minute stepping
	    minDate:"1/1/1900",               //set a minimum date
	  //  maxDate: ,     //set a maximum date (defaults to today +100 years)
	    language:'en',                  //sets language locale
	    defaultDate:"",                 //sets a default date, accepts js dates, strings and moment objects
	    disabledDates:[],               //an array of dates that cannot be selected
	    enabledDates:[],                //an array of dates that can be selected
	    useStrict: false,               //use "strict" when validating dates  
	    sideBySide: true,              //show the date and time picker side by side
	    daysOfWeekDisabled:[]          //for example use daysOfWeekDisabled: [0,6] to disable weekends
	});
});
</script>