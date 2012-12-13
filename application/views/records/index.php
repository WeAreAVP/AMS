<?php
if (!$isAjax)
{
    ?>

    <div class="row-fluid">
        <div class="span3" style="background-color: whiteSmoke;">
            <h4 style="margin: 6px 14px;">Assets</h4>
            </b>
            <div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " ><a style="color: white;" href="<?php echo site_url('records/index') ?>" >All Assets</a></div>
            <div style="padding: 8px;" > <a href="<?php echo "javascript:;"; //echo site_url('records/flagged') ?>" >Flagged</a></div>
    <?php $this->load->view('instantiations/_facet_search'); ?>
        </div>
        <div  class="span9" id="data_container">
<?php } ?>
        <ul class="nav nav-tabs">
            <li ><a href="javascript:;" style="color:#000;cursor:default;">View type :</a></li>
            <li id="simple_li" <?php if ($current_tab == 'simple'){ ?>class="active" <?php } ?>><a href="javascript:;" <?php if ($current_tab != 'simple'){ ?>onClick="change_view('simple')" <?php } ?> >Simple Table</a></li>
            <li id="full_table_li" <?php if ($current_tab == 'full_table'){ ?>class="active" <?php } ?>><a href="javascript:;" <?php if ($current_tab != 'full_table'){ ?>onClick="change_view('full_table')" <?php } ?> >Full Table</a></li>
            <li id="thumbnails_li" <?php if ($current_tab == 'thumbnails'){ ?>class="active" <?php } ?>><a href="javascript:;" >Thumbnails</a></li>
        </ul><?php
        if (isset($records) && ($total > 0))
        {?>
        <div style="width: 860px;">
        <?php if (isset($current_tab) && $current_tab == 'full_table'){$this->load->view('instantiations/_gear_dropdown'); }?>
            <div style="float: right;">
                <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong>
                <?php echo $this->ajax_pagination->create_links(); ?>
            </div>
        </div><?php 
		if (!isset($current_tab) || $current_tab == 'simple'){?>
        	<div style="width:865px;overflow:hidden;" id="simple_view">
            <table class="table table-bordered" id="assets_table" >
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
                    foreach ($records as $asset)
                    {

                        $guid_identifier = str_replace("(**)", "N/A", $asset->guid_identifier);
                        $local_identifier = str_replace("(**)", "N/A", $asset->local_identifier);
                        $asset_description = str_replace("(**)", "N/A", $asset->description);
                        $asset_title = str_replace("(**)", "N/A", $asset->asset_title);
                        ?>
                            <tr style="cursor: pointer;">
                                <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>
                                <td><?php
                            if ($guid_identifier)
                                echo $guid_identifier;
                            else
                                echo 'N/A';
                        ?>
                                </td>
                                <td><?php
                                if ($local_identifier)
                                    echo $local_identifier;
                                else
                                    echo 'N/A';
                        ?>
                                </td>
                                <td>

                                    <a href="<?php echo site_url('records/details/' . $asset->id) ?>" ><?php
                            if ($asset_title)
                                echo $asset_title;
                            else
                                echo 'N/A';
                        ?>
                                    </a>

                                </td>
                                <td><?php
                            if ($asset_description)
                            {

                                if (strlen($asset_description) > 160)
                                {
                                    $messages = str_split($asset_description, 160);
                                    echo $messages[0] . ' ...';
                                } else
                                {
                                    echo $asset_description;
                                }
                            }
                            else
                                echo 'N/A';
                        ?>
                                </td>
                            </tr><?php }
            ?>
                    </tbody>
                    <script> setTimeout(function (){updateSimpleDataTable();},500);</script>
               
            </table>
        </div><?php 
		}
		
		if (isset($current_tab) && $current_tab == 'full_table')
		{
			 ?>
        	<br clear="all"/>
        	<div style="width: 865px;overflow:hidden;" id="full_table_view" >
            <table class="table table-bordered" id="listing_table" style="margin-top:0px;margin-left: 1px;margin-bottom: 0px;"  >
                    <thead>
                        <tr >

                            <?php
                            if (!empty($this->column_order))
                            {

                                foreach ($this->column_order as $key => $value)
                                {
                                    $class = '';
                                    $type = $value['title'];
                                    if (isset($type) && !empty($type))
                                    {
                                        if ($type == 'flag')
                                        {
                                            ?>
                                            <th id="flag"><span style="float:left;" ><i class="icon-flag "></i></span></th><?php
                                        } else
                                        {

                                            if (!($this->frozen_column > $key))
                                                $class = 'drap-drop';
                                            if (in_array($type, array( "Local_ID", "Titles_Type", "Titles_Ref", "Titles_Source", "Description_Type", "Subjects", "Subjects_Ref", "Subjects_Source", "Genre", "Genre_Source", "Genre_Ref", "Creator_Name", "Creator_Affiliation", "Creator_Source", "Creator_Ref", "Creator_Role", "Creator_Role_Source", "Contributor_Name", "Contributor_Affiliation", "Contributor_Source", "Contributor_Ref", "Contributor_Role", "Contributor_Role_Source", "Contributor_Role_Ref", "Publisher_Name", "Publisher_Affiliation", "Publisher_Ref", "Publisher_Role", "Publisher_Role_Source", "Publisher_Role_Source_Ref", "Assets_Date", "Date_Type", "Coverage", "Coverage_Type", "Audience_Level", "Audience_Level_Source", "Audience_Level_Ref", "Audience_Rating", "Audience_Rating_Source", "Audience_Rating_Ref", "Annotation", "Annotation_Type", "Annotation_Ref", "Rights", "Rights_Link")))
                                            {
                                                $width = 'min-width:100px;';
                                            } else if ($type == 'Titles' || $type == 'Description' || $type =="AA_GUID")
                                            {
                                                $width = 'min-width:175px;';
                                            }
                                            echo '<th id="' . $value['title'] . '"  class="' . $class . '"><span style="float:left;' . $width . '">' . str_replace("_", ' ', $value['title']) . '</span></th>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody><?php
                    $def_setting = $this->config->item('assets_setting');
                    $def_setting = $def_setting['full'];
                    $body = '';
                    foreach ($records as $asset)
                    {

                        echo '<tr>';
                        foreach ($this->column_order as $row)
                        {
                            $type = $row['title'];
                            if (isset($type) && !empty($type))
                            {
                                if ($type == 'flag')
                                {
                                    echo '<td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="unflag"></i></td>';
                                }
								else
                                {
									
									if ($type == 'Titles')
									{
                                     	
										if (isset($asset->$def_setting[$row['title']]) && !empty($asset->$def_setting[$row['title']]))
                                        {
                                            $val = trim(str_replace("(**)", "N/A", $asset->$def_setting[$row['title']]));
                                            $asset_title = $val;
                                        }
										else
                                        {
                                            $asset_title = 'N/A';
                                        }
										
                                        $column ='<a href="'.site_url('records/details/' . $asset->id).'" >'.$asset_title.'</a>';
										
									
									}
									else if($type == 'Assets_Date')
									{
										 $column = ($asset->$def_setting[$row['title']] == 0) ? 'No Date' : date('Y-m-d', $asset->$def_setting[$row['title']]);
									}
									else if ($type != 'Description')
                                    {

                                        if (isset($asset->$def_setting[$row['title']]) && !empty($asset->$def_setting[$row['title']]))
                                        {
                                            $val = trim(str_replace("(**)", "N/A", $asset->$def_setting[$row['title']]));
                                            $column = $val;
                                        } else
                                        {
                                            $column = 'N/A';
                                        }
                                    }
									
									else
                                    {
                                        $des = str_replace("(**)", "N/A", $asset->$def_setting[$row['title']]);
                                        if (isset($des) && !empty($des) && strlen($des) > 160)
                                        {
                                            $messages = str_split($des, 160);
                                            $des = $messages[0] . ' ...';
                                        }
                                        $column = $des;
                                    }
                                    if (in_array($type, array( "Local_ID", "Titles_Type", "Titles_Ref", "Titles_Source", "Description_Type", "Subjects", "Subjects_Ref", "Subjects_Source", "Genre", "Genre_Source", "Genre_Ref", "Creator_Name", "Creator_Affiliation", "Creator_Source", "Creator_Ref", "Creator_Role", "Creator_Role_Source", "Contributor_Name", "Contributor_Affiliation", "Contributor_Source", "Contributor_Ref", "Contributor_Role", "Contributor_Role_Source", "Contributor_Role_Ref", "Publisher_Name", "Publisher_Affiliation", "Publisher_Ref", "Publisher_Role", "Publisher_Role_Source", "Publisher_Role_Source_Ref", "Assets_Date", "Date_Type", "Coverage", "Coverage_Type", "Audience_Level", "Audience_Level_Source", "Audience_Level_Ref", "Audience_Rating", "Audience_Rating_Source", "Audience_Rating_Ref", "Annotation", "Annotation_Type", "Annotation_Ref", "Rights", "Rights_Link")))
                                    {
                                        $width = 'min-width:100px;';
                                    } else if ($type == 'Titles' || $type == 'Description' || $type =="AA_GUID")
                                    {
                                        $width = 'min-width:175px;';
                                    }
                                    echo '<td><span style="float:left;' . $width . '">' . $column . '</span></td>';
                                }
                            }
                        }
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
            </table>
        </div><?php 
		
		}
		if( isset($current_tab) &&  $current_tab == 'thumbnails'){?>
        	<div style="overflow: auto;" id="thumbnails_view">
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
        <?php }?>
        <div style="text-align: right;width: 860px;"> <strong><?php echo $start; ?> - <?php echo $end; ?></strong> of <strong style="margin-right: 10px;"><?php echo $total; ?></strong> <?php echo $this->ajax_pagination->create_links(); ?> </div>
   <?php
                }
                else if ($start >= 1000)
                {
                    ?>
                    <div  style="text-align: center;width: 860px;margin-top: 50px;font-size: 20px;">Please refine your search</div><?php
                } else
                {
                    ?>
                   <div  style="text-align: center;width: 860px;margin-top: 50px;font-size: 20px;"> No Assets Found</div><?php }
                ?>
<?php
if (!$isAjax)
{
    ?>
        </div>
    </div>
<?php } ?>

