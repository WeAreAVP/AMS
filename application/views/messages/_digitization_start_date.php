<div id="type_1">
    <div class="control-group">
        <label class="control-label" for="shipping_instructions">Shipping Instructions:</label>
        <div class="controls">
            
            <textarea id="shipping_instructions" name="shipping_instructions"></textarea>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <textarea id="comments" name="comments"></textarea>


        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="estimated_complete_date">Estimated Complete Date:</label>
        <div class="controls">
            <input id="estimated_complete_date" name="estimated_complete_date"/>

        </div>
    </div>

</div>

<script type="text/javascript">
    $(function() {
        $( "#estimated_complete_date" ).datepicker();
        
    });
    function confirmBody(){
        shipping_instructions=$('#shipping_instructions').val();
        comments=$('#comments').val();
        estimated_complete_date=$('#estimated_complete_date').val();
        
        extras= {
            shipping_instructions: shipping_instructions,
            comments: comments,
						estimated_complete_date: estimated_complete_date
        };
                
        msg_body='Shipping Instructions: '+shipping_instructions+'\n'+
            'Comments: '+comments+'\n' 
        		'Estimated Complete Date: '+estimated_complete_date+'\n'; 
        
        
        $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
            '<div><strong>Subject: '+subject+'</strong></div><br/>'+
           
            '<div>Shipping Instructions: '+shipping_instructions+'</div>'+
            '<div>Comments: '+comments+'</div>'+
            '<div>Estimated Complete Date: '+estimated_complete_date+'</div>');
    }
</script>