<div class="modal hide" id="compose_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Send Message >> Confirm</h3>

    </div>
    <div class="modal-body" id="confirm_body">

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true" onclick="$('#compose_anchor').trigger('click');">Back</button>
        <button class="btn btn-primary"  data-dismiss="modal" onclick="sentEmail();">Send</button>
    </div>
</div>
<div class="modal hide" id="compose_to_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 700px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Compose Message</h3>
    </div>
    <div class="modal-body compose" >
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="receiver_id">To:</label>
                <div class="controls">
                    <input type="hidden" name="sender_id" id="sender_id" value="<?php echo $this->session->userdata['DX_user_id']; ?>"/>
                    <select id="receiver_id" name="receiver_id">
                        <?php foreach ($station_records as $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->station_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="msg_type">Message Type:</label>
                <div class="controls">
                    <select id="msg_type" onchange="typeForm();">
                        <option value="">Select</option>
                        <?php foreach ($this->config->item('messages_type') as $key => $value) {?>
                                          <option value="<?php echo $key; ?>"><?php echo $value; ?></option>           
                                               <?php }?>
                        
                    </select>
                    <span id="message_type_error">Please select message type</span>
                </div>
            </div>
            <div class="control-group" id="subject_div" style="display: none;">
                <label class="control-label" for="subject">Subject:</label>
                <div class="controls">
                    <input id="subject" name="subject"/>

                </div>
            </div>
            <div id="type_1" style="display: none;">
                <div class="control-group">
                    <label class="control-label" for="ship_date">Ship Date:</label>
                    <div class="controls">
                        <input id="ship_date" name="ship_date"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="ship_instructions">Shipping Instructions:</label>
                    <div class="controls">
                        <input id="ship_instructions" name="ship_instructions"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="comments_1">Comments:</label>
                    <div class="controls">
                        <input id="comments_1" name="comments_1"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="complete_date">Estimated Complete Date:</label>
                    <div class="controls">
                        <input id="complete_date" name="complete_date"/>

                    </div>
                </div>

            </div>
            <div id="type_2"  style="display: none;">
                <div class="control-group">
                    <label class="control-label" for="date_received_2">Date Received:</label>
                    <div class="controls">
                        <input id="date_received" name="date_received_2"/>

                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="comments_2">Comments:</label>
                    <div class="controls">
                        <input id="comments_2" name="comments_2"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="contact_detail_2">Crawford Contact Details:</label>
                    <div class="controls">
                        <input id="contact_detail_2" name="contact_detail_2"/>

                    </div>
                </div>

            </div>
            <div id="type_3"  style="display: none;">
                <div class="control-group">
                    <label class="control-label" for="date_received_3">Date Received:</label>
                    <div class="controls">
                        <input id="date_received_3" name="date_received_3"/>

                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="comments_3">Comments:</label>
                    <div class="controls">
                        <input id="comments_3" name="comments_3"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="contact_detail_3">Crawford Contact Details:</label>
                    <div class="controls">
                        <input id="contact_detail_3" name="contact_detail_3"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="digitization_review_date">30 day Digitization review:</label>
                    <div class="controls">
                        <input id="digitization_review_date" name="digitization_review_date"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="material_review_date">30 day Material Review:</label>
                    <div class="controls">
                        <input id="material_review_date" name="material_review_date"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="return_date">Material Return Date:</label>
                    <div class="controls">
                        <input id="return_date" name="return_date"/>

                    </div>
                </div>

            </div>
            <div id="type_4"  style="display: none;">
                <div class="control-group">
                    <label class="control-label" for="date_due">Date Due:</label>
                    <div class="controls">
                        <input id="date_due" name="date_due"/>

                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="comments_4">Comments:</label>
                    <div class="controls">
                        <input id="comments_4" name="comments_4"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="hd_list">Hard drive List:</label>
                    <div class="controls">
                        <input id="hd_list" name="hd_list"/>

                    </div>
                </div>

            </div>
            <div id="type_5"  style="display: none;">
                <div class="control-group">
                    <label class="control-label" for="review_end_date">Review End Date:</label>
                    <div class="controls">
                        <input id="review_end_date" name="review_end_date"/>

                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="comments_5">Comments:</label>
                    <div class="controls">
                        <input id="comments_5" name="comments_5"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="ftp_detail">FTP Details:</label>
                    <div class="controls">
                        <input id="ftp_detail" name="ftp_detail"/>

                    </div>
                </div>

            </div>

        </form>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"> Cancel</button>

        <button class="btn btn-primary" onclick="validateFormType();">Next</button>
    </div>
</div>


<script type="text/javascript">
    var extras=null;
    var to=null;
    var from=null;
    var subject=null;
    var type=null;
    var body=null;
    $(function() {
        $( "#ship_date" ).datepicker();
        $( "#complete_date" ).datepicker();
        $( "#date_received_2" ).datepicker();
        $( "#date_received_3" ).datepicker();
        $( "#digitization_review_date" ).datepicker();
        $( "#material_review_date" ).datepicker();
        $( "#date_due" ).datepicker();
        $( "#review_end_date" ).datepicker();
    });
    
    function typeForm(){
        $('#subject_div').show();
        type=$('#msg_type').val();
        for(i=1;i<6;i++)
            $('#type_'+i).hide();
                                    
        $('#type_'+type).show();
    }
   
    function validateFormType(){
        extras=new Array();
                        
        type=$('#msg_type').val();
        if(type=='')
            $('#message_type_error').show();
        else{
            $('#message_type_error').hide();
            $('#compose_anchor').trigger('click');
                                  
            to=$('#receiver_id').val();
            to_name=$("#receiver_id option[value='"+$('#receiver_id').val()+"']").text();
            from=$('#sender_id').val();
            subject=$('#subject').val();
                                  
            if(type==1){
                ship_date=$('#ship_date').val();
                ship_instructions=$('#ship_instructions').val();
                comments=$('#comments_1').val();
                complete_date=$('#complete_date').val();
                        
                extras='ship_date='+ship_date+',shipping_instructions='+ship_instructions+',comments='+comments+',estimated_complete_date='+complete_date;      
                html='<div>Ship Date: '+ship_date+'</div>'+
                    '<div>Shipping Instructions: '+ship_instructions+'</div>'+
                    '<div>Comments: '+comments+'</div>'+
                    '<div>Estimated Complete Date: '+complete_date+'</div>';               
                $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
                    '<div><strong>Subject: '+subject+'</strong></div><br/>'+
                    html );
                body='Ship Date: '+ship_date+'\n'+
                    'Shipping Instructions: '+ship_instructions+'\n'+
                    'Comments: '+comments+'\n'+
                    'Estimated Complete Date: '+complete_date+'\n';       
                                      
            }
            else if(type==2){
                date_received=$('#date_received_2').val();
                comments=$('#comments_2').val();
                contact_detail=$('#contact_detail_2').val();
                                
                            
                extras='date_received='+date_received+',comments='+comments+',crawford_contact_details='+contact_detail;
                html='<div>Date Received: '+date_received+'</div>'+
                    '<div>Comments: '+comments+'</div>'+
                    '<div>Crawford Contact Details: '+contact_detail+'</div>';           
                $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
                    '<div><strong>Subject: '+subject+'</strong></div><br/>'+
                    html );
                body='Date Received: '+date_received+'\n'+
                    'Comments: '+comments+'\n'+
                    'Crawford Contact Details: '+contact_detail+'\n';  
                                      
            }
            else if(type==3){
                date_received=$('#date_received_3').val();
                comments=$('#comments_3').val();
                contact_detail=$('#contact_detail_3').val();
                digitization_review_date=$('#digitization_review_date').val();
                material_review_date=$('#material_review_date').val();
                return_date=$('#return_date').val();
                                
                            
                extras='date_received='+date_received+',comments='+comments+',crawford_contact_details='+contact_detail+',30_day_digitization_review='+digitization_review_date+',30_day_material_review='+material_review_date+',material_return_date='+return_date;
                html= '<div>Date Received: '+date_received+'</div>'+
                    '<div>Comments: '+comments+'</div>'+
                    '<div>Crawford Contact Details: '+contact_detail+'</div>'+ 
                    '<div>30 day Digitization review: '+digitization_review_date+'</div>'+ 
                    '<div>30 day Material Review: '+material_review_date+'</div>'+ 
                    '<div>Material Return Date: '+return_date+'</div>';          
                $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
                    '<div><strong>Subject: '+subject+'</strong></div><br/>'+
                    html
            );
                body='Date Received: '+date_received+'\n'+
                    'Comments: '+comments+'\n'+
                    'Crawford Contact Details: '+contact_detail+'\n'+ 
                    '30 day Digitization review: '+digitization_review_date+'\n'+ 
                    '30 day Material Review: '+material_review_date+'\n'+ 
                    '>Material Return Date: '+return_date+'\n';
            }
            else if(type==4){
                date_due=$('#date_due').val();
                comments=$('#comments_4').val();
                hd_list=$('#hd_list').val();
                                
                extras='date_due='+date_due+',comments='+comments+',hard_drive_list='+hd_list;
                html=             '<div>Date Due: '+date_due+'</div>'+
                    '<div>Comments: '+comments+'</div>'+
                    '<div>Date Due: '+hd_list+'</div>';
                                
                                
                $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
                    '<div><strong>Subject: '+subject+'</strong></div><br/>'+
                    html );
                body=    'Date Due: '+date_due+'\n'+
                    'Comments: '+comments+'\n'+
                    'Date Due: '+hd_list+'\n';
            }
            else if(type==5){
                review_end_date=$('#review_end_date').val();
                comments=$('#comments_5').val();
                ftp_detail=$('#ftp_detail').val();
                                
                extras='review_end_date='+review_end_date+',comments='+comments+',ftp_details='+ftp_detail;
                html=        '<div>Review End Date: '+review_end_date+'</div>'+
                    '<div>Comments: '+comments+'</div>'+
                    '<div>FTP Details: '+ftp_detail+'</div>';   
                                
                $('#confirm_body').html('<div><strong>To: '+to_name+'</strong></div>'+
                    '<div><strong>Subject: '+subject+'</strong></div><br/>'+
                    html );
                body=  'Review End Date: '+review_end_date+'\n'+
                    'Comments: '+comments+'\n'+
                    'FTP Details: '+ftp_detail+'\n';
            }
            $('#confirm_anchor').trigger('click');
            
        }
                                    
    }
    function sentEmail(){
        $.ajax({
            type: 'POST', 
            url: '<?php echo site_url('messages/compose') ?>',
            data:{"extras":extras,to:to,from:from,subject:subject,type:type,html:body},
            success: function (result) { 
//               window.location.reload();
                    
                                
            }
        });
    }
</script>