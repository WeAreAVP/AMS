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
        <br/>
        <?php 
				if(isset($row->msg_extras) && $row->msg_extras!=NULL){
					$extras=json_decode($row->msg_extras);
					if(isset($extras) && !empty($extras))
					{
						foreach($extras as $key=>$value)
						{?>
							 <div><strong><?php echo ucwords(str_replace("_"," ",$key));?>: <?php echo $value;?></strong></div>
						<?php
            }
					}
					?>
        	
        <?php }?>
    </div>
</div>
<?php }else{?>

<?php }?>
