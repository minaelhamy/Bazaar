$(document).ready(function() {
    "use strict";
    // var showCurrentAtPos = $( ".timepicker" ).timepicker( "h", "showCurrentAtPos" );
    // $( ".timepicker" ).timepicker( "h", "showCurrentAtPos", 3 );
    if(time_format == 1)
    {
        $(".timepicker").timepicker({
            showMeridian: false,     
            use24hours: true,
            timeFormat: 'H:mm',
            
        });
    }else{
        $(".timepicker").timepicker();
    }
   
  });