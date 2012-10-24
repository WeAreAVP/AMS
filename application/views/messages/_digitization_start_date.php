<div id="type_1">
    <div class="control-group">
        <label class="control-label" for="ship_date">Ship Date:</label>
        <div class="controls">
            <input id="ship_date" name="ship_date"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="shipping_instructions">Shipping Instructions:</label>
        <div class="controls">
            <input id="shipping_instructions" name="shipping_instructions"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <input id="comments" name="comments"/>

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
        $( "#ship_date" ).datepicker();
        $( "#estimated_complete_date" ).datepicker();
        
    });
    function confirmBody(){
        ship_date=$('#ship_date').val();
        shipping_instructions=$('#shipping_instructions').val();
        comments=$('#comments').val();
        estimated_complete_date=$('#estimated_complete_date').val();
        
        extras= {
            ship_date: ship_date,
            shipping_instructions: shipping_instructions,
            comments: comments
        };
                
        body='Ship Date: '+ship_date+'\n'+
            'Shipping Instructions: '+shipping_instructions+'\n'+
            'Comments: '+comments+'\n' 
        'Estimated Complete Date: '+estimated_complete_date+'\n'; 
        
        
        $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
            '<div><strong>Subject: '+subject+'</strong></div><br/>'+
            '<div>Ship Date: '+ship_date+'</div>'+
            '<div>Shipping Instructions: '+shipping_instructions+'</div>'+
            '<div>Comments: '+comments+'</div>'+
            '<div>Estimated Complete Date: '+estimated_complete_date+'</div>');
    }
</script>