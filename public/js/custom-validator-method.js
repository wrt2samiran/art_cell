jQuery.validator.addMethod("toDateShouldGreatherFromDate", function (value, element,from_date) {
   
    if(value && from_date){
       
        var start_data_string       = String(from_date);
        var start_date_exploded     = start_data_string.split("/");
        var start_date_in_integer   = Number(start_date_exploded[2] + start_date_exploded[1] + start_date_exploded[0]);


        var end_date_string         = String(value);
        var end_date_exploded       = end_date_string.split("/");
        var end_date_in_integer     = Number(end_date_exploded[2] + end_date_exploded[1] + end_date_exploded[0]);

        return end_date_in_integer >= start_date_in_integer;
    } else {
        return true;
    }
});

/*validation rule for availablity endate should greater enddate */
jQuery.validator.addMethod("endDateShouldBeGreatherThanStartDate", function (value, element) {
    var start_date 	= $('#start_date').val();
   
    if(value && start_date){
       
        var start_data_string 		= String(start_date);
        var start_date_exploded 	= start_data_string.split("/");
        var start_date_in_integer 	= Number(start_date_exploded[2] + start_date_exploded[1] + start_date_exploded[0]);


        var end_date_string 		= String(value);
        var end_date_exploded 		= end_date_string.split("/");
        var end_date_in_integer 	= Number(end_date_exploded[2] + end_date_exploded[1] + end_date_exploded[0]);

        return end_date_in_integer >= start_date_in_integer;
    } else {
        return true;
    }
});


jQuery.validator.addMethod("toDateShouldBeGreatherThanFromDate", function (value, element) {
    var from_date  = $('#from_date').val();
   
    if(value && from_date){
       
        var start_data_string       = String(from_date);
        var start_date_exploded     = start_data_string.split("/");
        var start_date_in_integer   = Number(start_date_exploded[2] + start_date_exploded[1] + start_date_exploded[0]);


        var end_date_string         = String(value);
        var end_date_exploded       = end_date_string.split("/");
        var end_date_in_integer     = Number(end_date_exploded[2] + end_date_exploded[1] + end_date_exploded[0]);

        return end_date_in_integer >= start_date_in_integer;
    } else {
        return true;
    }
});



// validation rule  end time should greater start time //
$.validator.addMethod('endTimeShouldBeGreatherThanStartTime', function (value, element) {

 	var from_time_value 	= $('#start_time').val();
    
 	if(value && from_time_value){

  		var startTime 	= moment(from_time_value, "HH:mm:ss a");
  		var endTime  	= moment(value, "HH:mm:ss a");
   
  		return endTime > startTime;
 	} else {
    	return true;
 	}
});