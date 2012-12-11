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
 <div style="width: 860px;">
 
	<?php $this->load->view('instantiations/_gear_dropdown'); ?>
      <div style="float: right;">
        <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong>
        <?php echo $this->ajax_pagination->create_links(); ?>
      </div>
  </div>
   <div style="width:865px;overflow:hidden;display:<?php if($current_tab=='simple'){ echo 'block';}else{echo "none"; }?>;" id="simple_view">
   <table class="table table-bordered" id="assets_table" ><?php 
		if(isset($records) && ($total>0))
		{?>
        <thead>
            <tr>
	             <th style='width: 14px;'><span style="float:left;" ><i class="icon-flag "></i></span></th>
    	         <th style='width: 150px;'><span style="float:left;min-width: 100px;" >AA GUID</span></th>
        	     <th style='width: 110px;'><span style="float:left;min-width: 100px;" >Local ID</span></th>
            	<th style='width: 185px;'><span style="float:left;min-width: 175px;" >Titles</span></th>
             	<th style='width: 175px;'><span style="float:left;min-width: 175px;" >Description</span></th>
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
            <script> setTimeout(function (){updateSimpleDataTable();},1000);</script>
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
  <br clear="all"/>
   <div style="width: 865px;overflow:hidden; display:<?php if($current_tab=='full_table'){ echo 'block';}else{echo "none"; }?>;" id="full_table_view" >
   	<table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;"  ><?php 
		if(isset($records) && ($total>0))
		{
			?>
        <thead>
            <tr >
            
             <?php if(!empty($this->column_order))
			{
										
				foreach ($this->column_order as $key => $value)
                {
					$type = $value['title'];
					if(isset($type) && !empty($type))
					{
						if($type=='flag')
						{?>
							 <th id="flag"><span style="float:left;" ><i class="icon-flag "></i></span></th><?php
						}
						else
						{
							if (in_array($type,array("AA_GUID","Local_ID","Titles_Type","Titles_Ref","Titles_Source","Description_Type","Subjects","Subjects_Ref","Subjects_Source","Genre","Genre_Source","Genre_Ref","Creator_Name","Creator_Affiliation","Creator_Source","Creator_Ref","Creator_Role","Creator_Role_Source","Contributor_Name","Contributor_Affiliation","Contributor_Source","Contributor_Ref","Contributor_Role","Contributor_Role_Source","Contributor_Role_Ref","Publisher_Name","Publisher_Affiliation","Publisher_Ref","Publisher_Role","Publisher_Role_Source","Publisher_Role_Source_Ref","Assets_Date","Date_Type","Coverage","Coverage_Type","Audience_Level","Audience_Level_Source","Audience_Level_Ref","Audience_Rating","Audience_Rating_Source","Audience_Rating_Ref","Annotation","Annotation_Type","Annotation_Ref","Rights","Rights_Link")))
							{
								$width = 'min-width:100px;';
							}
							else if ($type == 'Titles' || $type=='Description')
							{
								$width = 'min-width:300px;';
							} 
							echo '<th id="' . $value['title'] . '"><span style="float:left;' . $width . '">' . str_replace("_", ' ', $value['title']) . '</span></th>';
						}
					}
				}
			}
			?>
           </tr>
           </thead>
            <tbody><?php 
			$def_setting=$this->config->item('assets_setting');
			$def_setting=$def_setting['full'];
			$body='';
			foreach($records as $asset)
			{ 
				
				echo '<tr>';
				foreach($this->column_order as $row)
				{
					$type = $row['title'];
					if(isset($type) && !empty($type))
					{
						if($type=='flag')
						{
							echo '<td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>';
						}
						else
						{
							if($type!='Description')
							{
												
								if(isset($asset->$def_setting[$row['title']]) && !empty($asset->$def_setting[$row['title']]))
								{
									$val=trim(str_replace("(**)","N/A",$asset->$def_setting[$row['title']]));
									$column =$val;
								}
								else
								{
									$column ='N/A';
								}
							}
							else
							{
								$des=str_replace("(**)","N/A",$asset->$def_setting[$row['title']]);
								if(isset($des) && !empty($des) && strlen($des)>160)
								{
									$messages = str_split($des , 160);
									$des=$messages[0].' ...';
								}
								$column =$des;
							}
							if (in_array($type,array("AA_GUID","Local_ID","Titles_Type","Titles_Ref","Titles_Source","Description_Type","Subjects","Subjects_Ref","Subjects_Source","Genre","Genre_Source","Genre_Ref","Creator_Name","Creator_Affiliation","Creator_Source","Creator_Ref","Creator_Role","Creator_Role_Source","Contributor_Name","Contributor_Affiliation","Contributor_Source","Contributor_Ref","Contributor_Role","Contributor_Role_Source","Contributor_Role_Ref","Publisher_Name","Publisher_Affiliation","Publisher_Ref","Publisher_Role","Publisher_Role_Source","Publisher_Role_Source_Ref","Assets_Date","Date_Type","Coverage","Coverage_Type","Audience_Level","Audience_Level_Source","Audience_Level_Ref","Audience_Rating","Audience_Rating_Source","Audience_Rating_Ref","Annotation","Annotation_Type","Annotation_Ref","Rights","Rights_Link")))
							{
								$width = 'min-width:100px;';
							}
							else if ($type == 'Titles' || $type=='Description')
							{
								$width = 'min-width:300px;';
							} 
							echo '<td><span style="float:left;' . $width . '">' . $column . '</span></td>';
						}
					}
				}
				echo '</tr>';
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

