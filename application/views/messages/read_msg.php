<?php 
if(isset($result) && !empty($result) && isset($result[0]))
{
	$row=$result[0];
	?>
<div >
    <div class="modal-header">
       <h3 id="userLabel"><?php echo $row->subject;?></h3>
    </div>
    <div class="modal-body">
				<div><strong><?php echo date("F d, Y",strtotime($row->created_at));?></strong></div>
        <div><strong>From: <?php echo $row->full_name;?></strong></div>
        <div><strong>Subject: <?php echo $row->subject;?></strong></div>
        <br/><br/>
        <?php 
				if(isset($row->msg_extras) && $row->msg_extras!=NULL){
					print_r(unserialize($row->msg_extras));
					?>
        	
        <?php }?>
    </div>
</div>
<?php }else{?>

<?php }?>
