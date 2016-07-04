function absecneAsterisk(ctr, day_limit){
	for(i=0; i < ctr; i++){
		if(i < day_limit){
			jQuery('#absence-ctr').append('<span class="glyphicon glyphicon-asterisk"></span>')
		} else{
			jQuery('#absence-ctr').append('<span class="glyphicon glyphicon-asterisk over-limit"></span>')
		}
	}
}
