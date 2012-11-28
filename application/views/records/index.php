<?php
if (!$isAjax)
{?>

<div class="row-fluid">
 <div class="span3">
    <?php $this->load->view('instantiations/_facet_search'); ?>
 </div>
 <div  class="span9" id="assets_container">
  <?php } ?>
  <div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
  <div style="text-align: right;width: 860px;"> <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong> <?php echo $this->ajax_pagination->create_links(); ?> </div>
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
      <td><p> <a href="<?php echo site_url('records/details/'.$asset->id)?>" >
        <?php 
			if($asset_title){
				echo $asset_title;
			}else{echo 'N/A';}
			?>
        </a> </p></td>
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
		else if($start>=1000)
		{?>
    Please refine your search
    <?php
    }else{?>
    No Assets Found
    <?php }?>
   </table>
  </div>
  <div style="text-align: right;width: 860px;"> <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong> <?php echo $this->ajax_pagination->create_links(); ?> </div>
  <?php
        if (!$isAjax)
        {
            ?>
 </div>
</div>
<script type="text/javascript">
function search_assets(parem)
{
	$('#assets_container').hide();
	var objJSON = eval("(function(){return " + parem + ";})()");
	$.ajax({
    	type: 'POST', 
        url: '<?php echo site_url('records/index') ?>/'+objJSON.page+'?'+$('#asset_frm').serialize(),
        success: function (result)
		{ 
          $('#assets_container').html(result);$('#assets_container').show();
        }
    });
}
</script>
<?php }?>
