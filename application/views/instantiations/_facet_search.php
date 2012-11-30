
<div id="search_bar"> 
    <form name="facet_search" id="form_search" method="post">

        <input type="hidden" name="current_tab" id="current_tab" value="<?php echo isset($this->session->userdata['current_tab']) ? $this->session->userdata['current_tab'] : '' ?>"  />

        <b>
            <h4>Filter</h4>
        </b>
        <div id="tokens">
            <div id="keyword_field_main" style="display: none;">
                <div class="filter-fileds"><b>Keyword</b></div>
                <div class="btn-img" id="" ><span class="search_keys">abc</span><i class="icon-remove-sign" onclick=""></i></div>
                <input type="hidden" id="keyword_field_main_search" name="keyword_field_main_search" value="" />
            </div>
            <div class="clearfix"></div>
            <!-- Organization Search Display Start  -->
            <?php
            if (isset($this->session->userdata['organization']) && $this->session->userdata['organization'] != '')
            {

                $organization = $this->session->userdata['organization'];
                $organization_array = explode('|||', $organization);
                ?>
                <div id="organization_main">
                    <div class="filter-fileds"><b>Organization</b></div>
                    <input type="hidden" id="organization_main_search" name="organization_main_search" value="<?php echo $organization; ?>" />
                    <?php
                    foreach ($organization_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','organization_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="organization_main" style="display: none;">
                    <div class="filter-fileds"><b>Organization</b></div>
                    <input type="hidden" id="organization_main_search" name="organization_main_search"/>
                </div>
            <?php } ?>
            <!-- Organization Search Display End  -->
            <div class="clearfix"></div>
            <!-- Nomination Status Search Display Start  -->
            <?php
            if (isset($this->session->userdata['nomination']) && $this->session->userdata['nomination'] != '')
            {

                $nomination = $this->session->userdata['nomination'];
                $nomination_array = explode('|||', $nomination);
                ?>
                <div id="nomination_status_main">
                    <div class="filter-fileds"><b>Nomination Status</b></div>
                    <input type="hidden" id="nomination_status_main_search" name="nomination_status_main_search" value="<?php echo $nomination; ?>" />
                    <?php
                    foreach ($nomination_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','nomination_status_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="nomination_status_main" style="display: none;">
                    <div class="filter-fileds"><b>Nomination Status</b></div>
                    <input type="hidden" id="nomination_status_main_search" name="nomination_status_main_search"/>
                </div>
            <?php } ?>
            <!-- Nomination Status Search Display End  -->
            <div class="clearfix"></div>
            <!-- Media Type Search Display Start  -->
            <?php
            if (isset($this->session->userdata['media_type']) && $this->session->userdata['media_type'] != '')
            {

                $media_type = $this->session->userdata['media_type'];
                $media_type_array = explode('|||', $media_type);
                ?>
                <div id="media_type_main">
                    <div class="filter-fileds"><b>Media Type</b></div>
                    <input type="hidden" id="media_type_main_search" name="media_type_main_search" value="<?php echo $media_type; ?>" />
                    <?php
                    foreach ($media_type_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','media_type_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="media_type_main" style="display: none;">
                    <div class="filter-fileds"><b>Media Type</b></div>
                    <input type="hidden" id="media_type_main_search" name="media_type_main_search"/>
                </div>
            <?php } ?>
            <!-- Media Type Search Display End  -->
            <div class="clearfix"></div>
            <!-- Physical Format Search Display Start  -->
            <?php
            if (isset($this->session->userdata['physical_format']) && $this->session->userdata['physical_format'] != '')
            {

                $physical_format = $this->session->userdata['physical_format'];
                $physical_format_array = explode('|||', $physical_format);
                ?>
                <div id="physical_format_main">
                    <div class="filter-fileds"><b>Physical Format</b></div>
                    <input type="hidden" id="physical_format_main_search" name="physical_format_main_search" value="<?php echo $physical_format; ?>" />
                    <?php
                    foreach ($physical_format_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','physical_format_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="physical_format_main" style="display: none;">
                    <div class="filter-fileds"><b>Physical Format</b></div>
                    <input type="hidden" id="physical_format_main_search" name="physical_format_main_search"/>
                </div>
            <?php } ?>
            <!-- Physical Format Search Display End  -->
            <div class="clearfix"></div>
            <!-- Digital Format Search Display Start  -->
            <?php
            if (isset($this->session->userdata['digital_format']) && $this->session->userdata['digital_format'] != '')
            {

                $digital_format = $this->session->userdata['digital_format'];
                $digital_format_array = explode('|||', $digital_format);
                ?>
                <div id="digital_format_main">
                    <div class="filter-fileds"><b>Digital Format</b></div>
                    <input type="hidden" id="digital_format_main_search" name="digital_format_main_search" value="<?php echo $digital_format; ?>" />
                    <?php
                    foreach ($digital_format_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','digital_format_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="digital_format_main" style="display: none;">
                    <div class="filter-fileds"><b>Digital Format</b></div>
                    <input type="hidden" id="digital_format_main_search" name="digital_format_main_search"/>
                </div>
            <?php } ?>
            <!-- Digital Format Search Display End  -->
            <div class="clearfix"></div>
            <!-- Generation Search Display Start  -->
            <?php
            if (isset($this->session->userdata['generation']) && $this->session->userdata['generation'] != '')
            {

                $generation = $this->session->userdata['generation'];
                $generation_array = explode('|||', $generation);
                ?>
                <div id="generation_main">
                    <div class="filter-fileds"><b>Generation</b></div>
                    <input type="hidden" id="generation_main_search" name="generation_main_search" value="<?php echo $generation; ?>" />
                    <?php
                    foreach ($generation_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','generation_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="generation_main" style="display: none;">
                    <div class="filter-fileds"><b>Generation</b></div>
                    <input type="hidden" id="generation_main_search" name="generation_main_search"/>
                </div>
            <?php } ?>
            <!-- Generation Search Display End  -->
            <div class="clearfix"></div>
            <!-- File Size Search Display Start  -->
            <?php
            if (isset($this->session->userdata['file_size']) && $this->session->userdata['file_size'] != '')
            {

                $file_size = $this->session->userdata['file_size'];
                $file_size_array = explode('|||', $file_size);
                ?>
                <div id="file_size_main">
                    <div class="filter-fileds"><b>File Size</b></div>
                    <input type="hidden" id="file_size_main_search" name="file_size_main_search" value="<?php echo $file_size; ?>" />
                    <?php
                    foreach ($file_size_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','file_size_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="file_size_main" style="display: none;">
                    <div class="filter-fileds"><b>File Size</b></div>
                    <input type="hidden" id="file_size_main_search" name="file_size_main_search"/>
                </div>
            <?php } ?>
            <!-- File Size Search Display End  -->
            <div class="clearfix"></div>
            <!-- Event Type Search Display Start  -->
            <?php
            if (isset($this->session->userdata['event_type']) && $this->session->userdata['event_type'] != '')
            {

                $event_type = $this->session->userdata['event_type'];
                $event_type_array = explode('|||', $event_type);
                ?>
                <div id="event_type_main">
                    <div class="filter-fileds"><b>Event Type</b></div>
                    <input type="hidden" id="event_type_main_search" name="event_type_main_search" value="<?php echo $event_type; ?>" />
                    <?php
                    foreach ($event_type_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','event_type_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="event_type_main" style="display: none;">
                    <div class="filter-fileds"><b>Event Type</b></div>
                    <input type="hidden" id="event_type_main_search" name="event_type_main_search"/>
                </div>
            <?php } ?>
            <!-- Event Type Search Display End  -->
            <div class="clearfix"></div>
            <!-- Event Outcome Search Display Start  -->
            <?php
            if (isset($this->session->userdata['event_outcome']) && $this->session->userdata['event_outcome'] != '')
            {

                $event_outcome = $this->session->userdata['event_outcome'];
                $event_outcome_array = explode('|||', $fevent_outcome);
                ?>
                <div id="event_outcome_main">
                    <div class="filter-fileds"><b>Event Outcome</b></div>
                    <input type="hidden" id="event_outcome_main_search" name="event_outcome_main_search" value="<?php echo $event_outcome; ?>" />
                    <?php
                    foreach ($event_outcome_array as $value)
                    {
                        $search_id = name_slug($value);
                        ?>
                        <div class="btn-img" id="<?php echo $search_id ?>" ><span class="search_keys"><?php echo $value; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($value); ?>','<?php echo $search_id ?>','event_outcome_main')"></i></div>
                    <?php } ?>
                </div>
                <?php
            } else
            {
                ?>

                <div id="event_outcome_main" style="display: none;">
                    <div class="filter-fileds"><b>Event Outcome</b></div>
                    <input type="hidden" id="event_outcome_main_search" name="event_outcome_main_search"/>
                </div>
            <?php } ?>
            <!-- Event Outcome Search Display End  -->
            <div class="clearfix"></div>

        </div>
        <div class="clearfix"></div>
        <div class="filter-fileds">
            <div><b>Search</b></div>
            <div>
                <input type="text" name="search" id="search" value=""/>
            </div>
        </div>
        <div class="filter-fileds">
            <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <span id="limit_field_text">Limit Search to Field</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown"><a href="#">Asset Fields <i class="icon-chevron-right" style="float: right;"></i></a>
                        <ul class="sub-menu dropdown-menu">
                            <li href="javascript://;" onclick="add_custom_token('Title','asset_title');"><a>Title</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Subject','asset_subject');">Subject</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Coverage','asset_coverage');">Coverage</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Genre','asset_genre');">Genre</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Publisher','asset_publisher_name');">Publisher</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Description','asset_description');">Description</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Creator Name','asset_creator_name');">Creator Name</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Creator Affiliation','asset_creator_affiliation');">Creator Affiliation</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Contributor Name','asset_contributor_name');">Contributor Name</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Contributor Affiliation','asset_contributor_affiliation');">Contributor Affiliation</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Rights Summaries','asset_rights');">Rights Summaries</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Annotations','asset_annotation');">Annotations</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#">Instantiation Fields <i class="icon-chevron-right" style="float: right;"></i></a>
                        <ul class="sub-menu dropdown-menu">
                            <li><a href="javascript://;" onclick="add_custom_token('ID','id');">ID</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('ID Source','instantiation_identifier');">Identifier Source</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Dimensions','instantiation_dimension');">Dimensions</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Unit of Measure','multi_assets');">Unit of Measure</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Standard','multi_assets');">Standard</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Location','multi_assets');">Location</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('File Size','multi_assets');">File Size</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Duration','multi_assets');">Duration</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Data Rate','multi_assets');">Data Rate</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Tracks','multi_assets');">Tracks</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Channel Configuration','multi_assets');">Channel Configuration</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Language','multi_assets');">Language</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Alternative Modes','multi_assets');">Alternative Modes</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Annotation','multi_assets');">Annotation</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Annotation Type','multi_assets');">Annotation Type</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Track Type','multi_assets');">Track Type</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Encoding','multi_assets');">Encoding</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Track Standard','multi_assets');">Track Standard</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Frame Rate','multi_assets');">Frame Rate</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Playback Speed','multi_assets');">Playback Speed</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Sampling Rate','multi_assets');">Sampling Rate</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Bit Depth','multi_assets');">Bit Depth</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Frame Size','multi_assets');">Frame Size</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Aspect Ratio','multi_assets');">Aspect Ratio</a></li>



                        </ul>
                    </li>
                </ul>
            </div>


        </div>
        <div class="filter-fileds">
            <div><input type="button" id="add_keyword" name="add_keyword" value="Add Keyword" class="btn btn-primary" onclick="add_token('','keyword_field_main');"/></div>
            <div><input type="button" id="reset_search" name="reset_search" value="Reset" class="btn" onclick="facet_search('{\"page\":0}');"/></div>
        </div>

        <!-- Organization  Start      -->
        <?php
        if (count($stations) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Organization</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($stations as $key => $value)
                {
                    ?>
                    <?php
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->station_name); ?>','organization_main');"><?php echo $value->station_name; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->station_name); ?>','organization_main');"><?php echo $value->station_name; ?></a></li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
        <!-- Organization  End      -->
        <!--  Nomination Status Start      -->
        <?php
        if (count($nomination_status) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Nomination Status</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($nomination_status as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->status); ?>','nomination_status_main');"><?php echo $value->status; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->status); ?>','nomination_status_main');"><?php echo $value->status; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($nomination_status) > 4)
                        {
                            echo count($nomination_status);
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!--  Nomination Status End      -->
        <!--  Media Type Start      -->
        <?php
        if (count($media_types) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Media Type</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($media_types as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->media_type); ?>','media_type_main');"><?php echo $value->media_type; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->media_type); ?>','media_type_main');"><?php echo $value->media_type; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($media_types) > 4)
                        {
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!--  Media Type End      -->
        <!--  Physical Format Start      -->
        <?php
        if (count($physical_formats) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Physical Format</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($physical_formats as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->format_name); ?>','physical_format_main');"><?php echo $value->format_name; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->format_name); ?>','physical_format_main');"><?php echo $value->format_name; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($physical_formats) > 4)
                        {
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- Physical Format End      -->
        <!--  Digital Format Start      -->
        <?php
        if (count($digital_formats) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Digital Format</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($digital_formats as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->format_name); ?>','digital_format_main');"><?php echo $value->format_name; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->format_name); ?>','digital_format_main');"><?php echo $value->format_name; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($digital_formats) > 4)
                        {
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- Digital Format End      -->
        <!--  Generation Start      -->
        <?php
        if (count($generations) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Generations</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($generations as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->generation); ?>','generation_main');"><?php echo $value->generation; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->generation); ?>','generation_main');"><?php echo $value->generation; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($generations) > 4)
                        {
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- Generation End      -->
        <!--  File Size Start      -->
        <?php
        if (count($file_size) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>File Size</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($file_size as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->file_size); ?>','file_size_main');"><?php echo $value->file_size; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->file_size); ?>','file_size_main');"><?php echo $value->file_size; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($file_size) > 4)
                        {
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- File Size End      -->
        <!--  Event Type Start      -->
        <?php
        if (count($event_types) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Event Type</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($event_types as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->event_type); ?>','event_type_main');"><?php echo $value->event_type; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->event_type); ?>','event_type_main');"><?php echo $value->event_type; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($event_types) > 4)
                        {
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- Event Type End      -->
        <!--  Event Type Start      -->
        <?php
        if (count($event_outcome) > 0)
        {
            ?>
            <div class="filter-fileds">
                <b>Event Type</b>
            </div>
            <div class="filter-fileds">
                <?php
                foreach ($event_outcome as $key => $value)
                {
                    if ($key < 4)
                    {
                        ?>
                        <div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->event_outcome); ?>','event_outcome_main');"><?php echo $value->event_outcome; ?></a></div>
                        <?php
                    } else if ($key == 4)
                    {
                        ?>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                                More
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <?php
                            } else
                            {
                                ?>
                                <li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value->event_outcome); ?>','event_outcome_main');"><?php echo $value->event_outcome; ?></a></li>  
                                <?php
                            }
                        }
                        if (count($event_outcome) > 4)
                        {
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- Event Type End      -->
    </form>
</div>
<script type="text/javascript">
    function add_token(name,type,isRemoved){
        if(type=='keyword_field_main'){
            if($('#search').val()!=''){
                name=$('#search').val();
                $('#add_keyword').hide(); 
                $('#reset_search').show();
                
                if(isRemoved==1){
                    $('#add_keyword').show(); 
                    $('#reset_search').hide();
                }
                else{
                    if($('#'+type+'_search').val().indexOf(name) < 0){
                        var random_id=rand(0,1000365);
                        slugName=make_slug_name(name);
                        var search_id=slugName+random_id;
                        $('#'+type).append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" style="float: right;" onclick="remove_token(\''+name+'\',\''+search_id+'\',\''+type+'\')"></i></div>');
                        $('#'+type).show();
                    }
                }
            }
           
        }
        if(isRemoved!=1 && type!='keyword_field_main'){
            if($('#'+type+'_search').val().indexOf(name) < 0){
                var random_id=rand(0,1000365);
                slugName=make_slug_name(name);
                var search_id=slugName+random_id;
                $('#'+type).append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" style="float: right;" onclick="remove_token(\''+name+'\',\''+search_id+'\',\''+type+'\')"></i></div>');
                $('#'+type).show();
            }
        }
        var my_search_words='';
        $('#'+type+'_search').val('');
        $("#"+type+" .search_keys").each(function(index) {
            if(index==0)
                my_search_words=$(this).text();
            else
                my_search_words+='|||'+$(this).text();
            
        });
        if(my_search_words!='' && typeof(my_search_words)!=undefined)
        {
            $('#'+type+'_search').val(my_search_words);
            console.log(my_search_words);
            
        }
        facet_search('{"page":0}');
        
    }
    var customFieldName='';
    var customColumnName='';
    function add_custom_token(fieldName,columnName){
        text=$('#search').val();
        customFieldName=fieldName;
        customColumnName=columnName;
        $('#limit_field_text').html(fieldName);
    }
    function make_slug_name(string){
        string = string.split('/').join('-');
        string = string.split('??').join('q');
        string = string.split(' ').join('');
        string = string.split('(').join('-');
        string = string.split(')').join('-');
        string = string.split(',').join('-');
        string = string.split('.').join('-');
        string = string.split('"').join('-');
        string = string.split('\'').join('-');
        string = string.split(':').join('-');
        string = string.toLowerCase();
        return string;
    }
    function remove_token(name,id,type)
    {
        $("#"+id).remove();
        if($('#'+type+' div').length<=1){
            $('#'+type).hide();
        }
        add_token(name,type,1);        
    }
    function facet_search(param)
    {
        $.blockUI({
            css: { 
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#fff',
                zIndex:999999
            }
        }); 
        var objJSON = eval("(function(){return " + param + ";})()");
        $.ajax({
            type: 'POST', 
            url: '<?php echo $facet_search_url ?>/'+objJSON.page,
            data:$('#form_search').serialize(),
            success: function (result)
            { 
                $('#data_container').html(result); 
                $.unblockUI();
                                
            }
        });
    }
    function change_view(id)
    {
        $('#current_tab').val(id);
        $.ajax({
            type: 'POST', 
            url: '<?php echo site_url('records/set_current_tab') ?>/'+id,
        });
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
