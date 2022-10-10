let limited_columns = 1;
function add_more() 
{
	
	if (limited_columns < 5) 
	{
		var box_count = jQuery("#box_count").val();
		box_count++;
		limited_columns++;
		jQuery("#box_count").val(box_count);
         
		jQuery("#wrap").append('<div class="my_box" id="box_loop_' + box_count + '"> <div class="field_box"><input type="textbox" name="widget-owt_wp_widget['+box_count+'][question][]" id="one"> <br><textarea  name="widget-owt_wp_widget['+box_count+'][answer][]" id="one" class="mywade "></textarea> </div><div class="button_box"><input type="button" name="submit" id="submit" value="Remove" onclick=remove_more("' + box_count + '")> </div></div>');
           



	}

}


function remove_more(box_count) 
{
	limited_columns = limited_columns - 1;
	jQuery("#box_loop_" + box_count).remove();
	var box_count = jQuery("#box_count").val();
	box_count--;
	jQuery("#box_count").val(box_count);
}
