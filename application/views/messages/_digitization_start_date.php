<div id="type_1">
    <div class="control-group">
        <label class="control-label" for="shipping_instructions">Shipping Instructions:</label>
        <div class="controls">
            <textarea id="shipping_instructions" name="shipping_instructions"  rows="4" cols="80"></textarea>
            <span id="shipping_instructions_error" style="display: none;">Please Enter Shipping Instructions</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <textarea id="comments" name="comments" rows="4" cols="80"></textarea>
            <span id="comments_error" style="display: none;">Please Enter Comments</span>


        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="estimated_complete_date">Estimated Complete Date:</label>
        <div class="controls">
            <input id="estimated_complete_date" name="estimated_complete_date"/>
            <span id="estimated_complete_date_error" style="display: none;">Please Enter Estimated Complete Date</span>

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