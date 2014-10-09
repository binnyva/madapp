$(function() {
	$(".filter-multiselect").on('keyup', function(e) {
		var filter = this.value.toLowerCase();
		var target = $(this).attr("target-field");

		$("#"+target+" > option").each(function(){ 
			if(!this.selected) { // Make sure the selected ones are shown at all times.
				if(this.text.toLowerCase().search(filter) == -1) $(this).hide();
				else $(this).show();
			}
		});
	});
}) 
