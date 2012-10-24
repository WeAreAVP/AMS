<div id="type_4">
    <div class="control-group">
        <label class="control-label" for="date_due">Date Due:</label>
        <div class="controls">
            <input id="date_due" name="date_due"/>

        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="comments">Comments:</label>
        <div class="controls">
            <input id="comments" name="comments"/>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="hd_list">Hard drive List:</label>
        <div class="controls">
            <input id="hd_list" name="hd_list"/>

        </div>
    </div>

</div>
<script type="text/javascript">
    $(function() {
        $( "#date_due" ).datepicker();
    });
    function confirmBody(){
        date_due=$('#date_due').val();
        comments=$('#comments').val();
        hd_list=$('#hd_list').val();
                                
                
                
        extras= {
            date_due: date_due,
            comments: comments,
            hard_drive_list: hd_list
        };
        
        $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
            '<div><strong>Subject: '+subject+'</strong></div><br/>'+
            '<div>Date Due: '+date_due+'</div>'+
            '<div>Comments: '+comments+'</div>'+
            '<div>Date Due: '+hd_list+'</div>' );
        
        body='Date Due: '+date_due+'\n'+
            'Comments: '+comments+'\n'+
            'Date Due: '+hd_list+'\n';
    }
</script>