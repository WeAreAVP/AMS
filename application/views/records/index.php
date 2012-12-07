<?php
if (!$isAjax)
{?>

<div class="row-fluid">
 <div class="span3" style="background-color: whiteSmoke;">
     <h4 style="margin: 6px 14px;">Assets</h4>
   </b>
   <div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " ><a style="color: white;" href="<?php echo site_url('records/index') ?>" >All Assets</a></div>
   <div style="padding: 8px;" > <a href="<?php echo "javascript:;";//echo site_url('records/flagged') ?>" >Flagged</a></div>
    <?php $this->load->view('instantiations/_facet_search'); ?>
 </div>
 <div  class="span9" id="data_container">
 <?php } ?>
  <ul class="nav nav-tabs">
   <li ><a href="javascript:;" style="color:#000;cursor:default;">View type :</a></li>
   <li id="simple_li" <?php if($current_tab=='simple'){?>class="active" <?php }?>><a href="javascript:;" onClick="change_view('simple')">Simple Table</a></li>
   <li id="full_table_li" <?php if($current_tab=='full_table'){?>class="active" <?php }?>><a href="javascript:;" onClick="change_view('full_table')">Full Table</a></li>
   <li id="thumbnails_li" <?php if($current_tab=='thumbnails'){?>class="active" <?php }?>><a href="javascript:;" >Thumbnails</a></li>
  </ul>

  <div style="text-align: right;width: 860px;">
  	<strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong>
	<?php echo $this->ajax_pagination->create_links(); ?>
  </div>
   <div style="width:865px;overflow:hidden;display:<?php if($current_tab=='simple'){ echo 'block';}else{echo "none"; }?>;" id="simple_view">
   <table class="tablesorter table-freeze-custom table-bordered freeze-my-column" id="assets_table" ><?php 
		if(isset($records) && ($total>0))
		{?>
        <thead>
            <tr>
	             <th><span style="float:left;" ><i class="icon-flag "></i></span></th>
    	         <th><span style="float:left;min-width: 100px;" >AA GUID</span></th>
        	     <th><span style="float:left;min-width: 100px;" >Local ID</span></th>
            	<th ><span style="float:left;min-width: 100px;" >Titles</span></th>
             	<th ><span style="float:left;min-width: 100px;" >Description</span></th>
            </tr>
            </thead>
            <tbody><?php 
			foreach($records as $asset)
			{
				
				$guid_identifier= str_replace("(**)","N/A",$asset->guid_identifier);
				$local_identifier= str_replace("(**)","N/A",$asset->local_identifier);
				$asset_description=str_replace("(**)","N/A",$asset->description);
				$asset_title=str_replace("(**)","N/A",$asset->asset_title);?>
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
                  	
                    	<a href="<?php echo site_url('records/details/'.$asset->id)?>" ><?php 
							if($asset_title)
								echo $asset_title;
							else
								echo 'N/A';?>
						</a>
                    
                  </td>
                  <td><?php 
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
   <div style="width:865px;overflow:hidden;display:<?php if($current_tab=='full_table_view'){ echo 'block';}else{echo "none"; }?>;" id="full_table_view" >
   	<table class="tablesorter table-freeze-custom table-bordered freeze-my-column1" id="assets_table1" style="margin-top:0px;margin-left: 1px; "  ><?php 
		if(isset($records) && ($total>0))
		{
			if(!empty($this->column_order))
			{
				$titles ='<th><span style="float:left;"><i class="icon-flag "></i></span></th>';
				foreach($this->column_order as $row)
				{
					if($row['hidden']==0)
					{
						$titles .=	'<th><span style="float:left;min-width: 100px;" >'.str_replace("_",' ',$row['title']).'</span></th>';
					}
					else
					{
						$titles .=	'<th style="display:none"><span style="float:left;min-width: 100px;" >'.str_replace("_",' ',$row['title']).'</span></th>';
					}
				}
				
			}?>
        <thead>
            <tr >
             <?php echo $titles;?>
           </tr>
           </thead>
            <tbody><?php 
			$body='';
			foreach($records as $asset)
			{ 
				
				foreach($this->column_order as $row)
				{
					if($row['hidden']==0)
					{
						if($row['field']!='description')
						{
								$body .='<td>'.str_replace("(**)","N/A",$asset->$row['field']).'</td>';
						}
						else
						{
							$des=str_replace("(**)","N/A",$asset->$row['field']);
							if(strlen($asset->$row['field'])>160)
							{
								$messages = str_split($asset->$row['field'] , 160);
								$des=$messages[0].' ...';
							}
							$body .='<td>'.$des.'</td>';
						}
					}
					else
					{
						$body .='<td style="display:none">'.str_replace("(**)","N/A",$asset->$row['field']).'</td>';
					}
				}?>
     			<tr><?php echo $body ?></tr><?php 
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
 	<div style="overflow: auto;display:<?php if($current_tab=='thumbnails'){ echo 'block';}else{echo "none"; }?>;" id="thumbnails_view">
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
<?php }?>

