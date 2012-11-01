<div id="type_3">
    <div class="control-group">
        <label class="control-label" for="est_ship_date">Est. Ship Date:</label>
        <div class="controls">
            <input id="est_ship_date" name="est_ship_date"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="actual_ship_date">Actual Ship Date:</label>
        <div class="controls">
            <input id="actual_ship_date" name="actual_ship_date"/>

        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <input id="comments" name="comments"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="crawford_contact_details">Crawford Contact Details:</label>
        <div class="controls">
            <input id="crawford_contact_details" name="crawford_contact_details"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="day_digitization_review">30 day Digitization review:</label>
        <div class="controls">
            <input id="day_digitization_review" name="day_digitization_review"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="day_material_review">30 day Material Review:</label>
        <div class="controls">
            <input id="day_material_review" name="day_material_review"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="material_return_date">Material Return Date:</label>
        <div class="controls">
            <input id="material_return_date" name="material_return_date"/>

        </div>
    </div>
 		<div class="control-group">
        <label class="control-label" for="assets_list">Asset List:</label>
        <div class="controls">
            <input id="assets_list" name="assets_list"/>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
			 
        $( "#est_ship_date" ).datepicker();
				$( "#actual_ship_date" ).datepicker();
        $( "#day_digitization_review" ).datepicker();
        $( "#day_material_review" ).datepicker();
        $( "#material_return_date" ).datepicker();
    });
    function confirmBody(){
				est_ship_date=$( "#est_ship_date" ).datepicker();
				actual_ship_date=$( "#actual_ship_date" ).datepicker();
         comments=$('#comments').val();
        crawford_contact_details=$('#crawford_contact_details').val();
        digitization_review_date=$('#day_digitization_review').val();
        material_review_date=$('#day_material_review').val();
        return_date=$('#material_return_date').val();
				assets_list=$('#assets_list').val();
                                
                            
               
        extras= {
            est_ship_date: est_ship_date,
						actual_ship_date: actual_ship_date,
            comments: comments,
            crawford_contact_details: crawford_contact_details,
            day_digitization_review: digitization_review_date,
            day_material_review: material_review_date,
            material_return_date: return_date,
						assets_list:assets_list
        };
                
                  
        $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
            '<div><strong>Subject: '+subject+'</strong></div><br/>'+
            '<div>Est. Ship Date: '+est_ship_date+'</div>'+
						'<div>Actual Ship Date: '+actual_ship_date+'</div>'+
            '<div>Comments: '+comments+'</div>'+
            '<div>Crawford Contact Details: '+crawford_contact_details+'</div>'+ 
            '<div>30 day Digitization review: '+digitization_review_date+'</div>'+ 
            '<div>30 day Material Review: '+material_review_date+'</div>'+ 
            '<div>Material Return Date: '+return_date+'</div>'+ 
						'<div>Asset List: '+assets_list+'</div>'
    );
        msg_body='Est. Ship Date: '+est_ship_date+'\n'+
						'Actual Ship Date: '+actual_ship_date+'\n'+
            'Comments: '+comments+'\n'+
            'Crawford Contact Details: '+crawford_contact_details+'\n'+ 
            '30 day Digitization review: '+digitization_review_date+'\n'+ 
            '30 day Material Review: '+material_review_date+'\n'+ 
            'Material Return Date: '+return_date+'\n'+
						'Asset List: '+assets_list+'\n';
    }
</script>