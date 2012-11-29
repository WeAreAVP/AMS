<?php
if (!$isAjax)
{?>

<div class="row-fluid">
 <div class="span3">
 	<h4>Assets</h4>
   </b>
   <div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " ><a style="color: white;" href="<?php echo site_url('records/index') ?>" >All Assets</a></div>
   <div style="padding: 8px;" > <a href="<?php echo "javascrip:;";//echo site_url('records/flagged') ?>" >Flagged</a></div>
    <?php $this->load->view('instantiations/_facet_search'); ?>
 </div>
 <div  class="span9" id="assets_container">
  <ul class="nav nav-tabs">
   <li ><a href="javascript:;" style="color:#000;cursor:default;">View type :</a></li>
   <li id="simple_li" class="active"><a href="javascript:;" onClick="change_view('simple')">Simple Table</a></li>
   <li id="full_table_li"><a href="javascript:;" onClick="change_view('full_table')">Full Table</a></li>
   <li id="thumbnails_li"><a href="javascript:;" >Thumbnails</a></li>
  </ul>
<?php } ?>
  <div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
  <div style="text-align: right;width: 860px;"> <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong> <?php echo $this->ajax_pagination->create_links(); ?> </div>
   <div style="overflow: auto;height: 400px;" id="simple_view">
   <table class="tablesorter table table-bordered" ><?php 
		if(isset($records) && ($total>0))
		{?>
            <tr>
             <td style="vertical-align:middle;font-weight:bold"><i class="icon-flag "></i></td>
             <th style="width:30%">AA GUID</th>
             <td style="vertical-align:middle;font-weight:bold;width:30%">Local ID</td>
             <td style="vertical-align:middle;font-weight:bold;width:15%">Titles</td>
             <td style="vertical-align:middle;font-weight:bold;">Description</td>
            </tr>
            <tbody><?php 
			foreach($records as $asset)
			{
				$guid_identifier=$asset->guid_identifier;
				$local_identifier=$asset->local_identifier;
				$asset_description=$asset->description;
				$asset_title=$asset->asset_titles;?>
				<tr style="cursor: pointer;">
			      <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>
                  <td><?php 
					if($guid_identifier)
						echo $guid_identifier;
					else
						echo 'N/A';?>
                  </td>
                  <td><?php 
				  	if($local_identifier)
						echo $local_identifier;
					else
						echo 'N/A';?>
                  </td>
			      <td>
                  	<p>
                    	<a href="<?php echo site_url('records/details/'.$asset->id)?>" ><?php 
							if($asset_title)
								echo $asset_title;
							else
								echo 'N/A';?>
						</a>
                    </p>
                  </td>
                  <td><p><?php 
					if($asset_description)
					{
						if(strlen($asset_description)>160)
						{
							$messages = str_split($asset_description , 160);
							echo $messages[0].' ...';
						}
						else
						{
							echo $asset_description;
						}
					}
					else 
						echo 'N/A';?>
			       </p>
                  </td>
     			</tr><?php 
			}?>
			</tbody>
    <?php }
		else if($start>=1000)
		{?>
    		Please refine your search<?php
	    }
		else 
		{?>
    		No Assets Found<?php 
		}?>
   </table>
  </div>
   <div style="overflow: scroll;display:none;" id="full_table_view" >
   	<table class="tablesorter table table-bordered" ><?php 
		if(isset($records) && ($total>0))
		{?>
            <tr>
             <td style="vertical-align:middle;font-weight:bold"><i class="icon-flag "></i></td>
             <th style="width:30%">AA GUID</th>
             <td style="vertical-align:middle;font-weight:bold;width:30%">Local ID</td>
             <td style="vertical-align:middle;font-weight:bold;width:15%">Titles</td>
             <td style="vertical-align:middle;font-weight:bold;width:15%">Titles Type</td>
             <td style="vertical-align:middle;font-weight:bold;width:15%">Titles Ref</td>
             <td style="vertical-align:middle;font-weight:bold;width:15%">Titles Source</td>
              <td style="vertical-align:middle;font-weight:bold;width:15%">Subjects</td>
               <td style="vertical-align:middle;font-weight:bold;width:15%">Subjects Ref</td>
                <td style="vertical-align:middle;font-weight:bold;width:15%">Subjects Source</td>
             <td style="vertical-align:middle;font-weight:bold;">Description</td>
            </tr>
            <tbody><?php 
			foreach($records as $asset)
			{
				$guid_identifier=$asset->guid_identifier;
				$local_identifier=$asset->local_identifier;
				$asset_description=$asset->description;
				$asset_title=$asset->asset_titles;?>
				<tr style="cursor: pointer;">
			      <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>
                  <td><?php 
					if($guid_identifier)
						echo $guid_identifier;
					else
						echo 'N/A';?>
                  </td>
                  <td><?php 
				  	if($local_identifier)
						echo $local_identifier;
					else
						echo 'N/A';?>
                  </td>
			      <td>
                  	<p>
                    	<a href="<?php echo site_url('records/details/'.$asset->id)?>" ><?php echo $asset_title;?>	</a>
                    </p>
                  </td>
                   <td><?php 
				  		echo $asset->asset_titles_type;
					?>
                  </td>
                  <td><?php 		  	
						echo $asset->asset_titles_ref;
					?>
                  </td>
                  <td><?php 
				  		echo $asset->asset_titles_source;?>
                  </td>
                
                 
                  <td><p><?php 
					if($asset_description)
					{
						if(strlen($asset_description)>160)
						{
							$messages = str_split($asset_description , 160);
							echo $messages[0].' ...';
						}
						else
						{
							echo $asset_description;
						}
					}
					else 
						echo 'N/A';?>
			       </p>
                  </td>
     			</tr><?php 
			}?>
			</tbody>
    <?php }
		else if($start>=1000)
		{?>
    		Please refine your search<?php
	    }
		else 
		{?>
    		No Assets Found<?php 
		}?>
       </table>
</div>
 	<div style="overflow: auto;display:none;" id="thumbnails_view">
        <div class="span3 title">
        <div class="unflag"></div>
        <img width="250px" src="http://placehold.it/140x140" alt="" />
        <h4>Title</h4>
        <p>10:05-10:20 BAKER SPRING
        RESEARCH FELLOW AND
        NATIONAL SECURITY AT THE
        HERITAGE FOUNDATION http://
        www.heritage.org 202-608-6112,
        CT. JOE DOUGHERTY 202
        546-4400 FAR OUT RUMSFELD
        PROPOSES TO | On the Line</p>
        </div>
        <div class="span3 title">
        <div class="flag"></div>
        <img width="250px" src="http://placehold.it/140x140" alt="" />
        <h4>Title</h4>
        <p>Title of the Asset</p>
        </div>
        <div class="span3 title">
        <div class="flag"></div>
        <img width="250px" src="http://placehold.it/140x140" alt="" />
        <h4>Title</h4>
        <p>Title of the Asset</p>
        </div>
        <div class="span3 title">
        <div class="flag"></div>
        <img width="250px" src="http://placehold.it/140x140" alt="" />
        <h4>Title</h4>
        <p>Title of the Asset</p>
        </div>
  </div>
  <div style="text-align: right;width: 860px;"> <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong> <?php echo $this->ajax_pagination->create_links(); ?> </div>
  <?php
        if (!$isAjax)
        {?>
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
<?php }?>
