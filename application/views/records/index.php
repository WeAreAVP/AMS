
<div class="row-fluid">
 <div class="span3">
  <div id="search_bar"> <b>
   <h4>Assets</h4>
   </b>
   <?php /*?><div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " ><a style="color: white;" href="<?php echo site_url('records/index') ?>" >All Assets</a></div>
   <div style="padding: 8px;" > <a href="<?php echo site_url('records/flagged') ?>" >Flagged</a></div>
   <br/><?php */?>
   <br/>
   <b>
   <h4>FILTER ASSETS</h4>
   </b>
   <form name="asset_frm" id="asset_frm" method="post" >
    <div>
     <div> GUID </div>
     <div>
      <input type="text" name="guid" id="guid"/>
     </div>
     <div> FORMAT </div>
     <div>
      <input type="text" name="format" id="format"/>
     </div>
     <div> TITLE </div>
     <div>
      <input type="text" name="title" id="title"/>
     </div>
     <div> STATION NAME </div>
     <div>
      <input type="text" name="station_name" id="station_name"/>
     </div>
     <div> STATION ID </div>
     <div>
      <input type="text" name="station_id" id="station_id"/>
     </div>
     <div> NOMINATION STATUS </div>
     <div>
      <input type="text" name="nomination_status" id="nomination_status"/>
     </div>
     <div> DIGITIZED </div>
     <div>
      <input type="text" name="digitized" id="digitized"/>
     </div>
     <div> MEDIA TYPE </div>
     <div>
      <input type="text" name="media_type" id="media_type"/>
     </div>
     <div> STATE </div>
     <div>
      <input type="text" name="state" id="state"/>
     </div>
    </div>
    <br/>
    <input type="button" name"search" value="Search" />
   </form>
  </div>
 </div>
 <div  class="span9">
  <div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
  <div style="text-align: right;width: 860px;"> <strong>1 - <?php echo $count; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong> <?php echo $this->pagination->create_links(); ?> </div>
  <div style="overflow: auto;height: 400px;" id="simple_view">
   <table class="tablesorter table table-bordered" >
    <?php 
		if(isset($records) && ($total>0))
		{
			?>
    <tr>
     <td style="vertical-align:middle;font-weight:bold"><i class="icon-flag "></i></td>
     <th style="width:30%">AA GUID</th>
     <td style="vertical-align:middle;font-weight:bold;width:30%">Local ID</td>
     <td style="vertical-align:middle;font-weight:bold;width:15%">Title</td>
     <td style="vertical-align:middle;font-weight:bold;">Description</td>
    </tr>
    <tbody>
     <?php 
			foreach($records as $asset)
			{
				$guid_identifier=$asset->guid_identifier;
				$local_identifier=$asset->local_identifier;
				$asset_description=$asset->description;
				$asset_title=$asset->title;			
		?>
     <tr style="cursor: pointer;">
      <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>
      <td><?php 
			if($guid_identifier){
				echo $guid_identifier;
			}else{echo 'N/A';}
			?></td>
      <td><?php 
			if($local_identifier){
				echo $local_identifier;
			}else{echo 'N/A';}
			?></td>
      <td><p>
        <a href="<?php echo site_url('records/details/'.$asset->id)?>" ><?php 
			if($asset_title){
				echo $asset_title;
			}else{echo 'N/A';}
			?>
            </a>
       </p></td>
      <td><p>
        <?php 
			if($asset_description){
				echo $asset_description;
			}else{echo 'N/A';}
			?>
       </p></td>
     </tr>
     <?php 
	 	}
	 ?>
    </tbody>
    <?php }
		else
		{?>
    No Assets Found
    <?php }?>
   </table>
  </div>
  <?php 
		if(isset($records) && ($total>0))
		{
			?>
  <div style="text-align: right;width: 860px;"> <strong>1 - <?php echo $count; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong> <?php echo $this->pagination->create_links(); ?> </div>
  <?php }?>
 </div>
</div>
<script>
function change_view(id)
{
	$('#simple_view').hide();
	$('#full_table_view').hide();
	$('#thumbnails_view').hide();
	$('#simple_li').removeClass("active");
	$('#full_table_li').removeClass("active");
	$('#thumbnails_li').removeClass("active");
	$('#'+id+'_view').show();
	$('#'+id+'_li').addClass("active");
	
}
</script> 
