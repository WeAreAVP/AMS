
<div id="search_bar" class="facet-search"> 
    <form name="form_search" id="form_search" method="post" onsubmit="return false;">

        <input type="hidden" name="current_tab" id="current_tab" value="<?php echo isset($this->session->userdata['current_tab']) ? $this->session->userdata['current_tab'] : '' ?>"  />

        <b>
            <h4>Filter</h4>
        </b>
        <div id="tokens">
            <?php
            if (isset($this->session->userdata['custom_search']) && $this->session->userdata['custom_search'] != '')
            {

                $custom_search = $this->session->userdata['custom_search'];

                $column_name = explode('@', $custom_search);
                if (count($column_name) > 1)
                {
                    $column_name = implode('', $column_name);
                    $column_name = explode('|||', $column_name);
                    $column_name = ': ' . $get_column_name[trim($column_name[0])];
                }

                else
                    $column_name = ': All';

                $custom_search = explode('|||', $custom_search);
                $custom_search = $custom_search[count($custom_search) - 2];
                $search_id = name_slug($custom_search);
                ?>
                <div id="keyword_field_main">
                    <div class="filter-fileds"><b id="keyword_field_name">Keyword<?php echo $column_name; ?></b></div>
                    <div class="btn-img" id="<?php echo $search_id; ?>" ><span class="search_keys"><?php echo $custom_search; ?></span><i class="icon-remove-sign" style="float: right;" onclick="remove_token('<?php echo htmlentities($custom_search); ?>','<?php echo $search_id; ?>','keyword_field_main');"></i></div>
                    <input type="hidden" id="keyword_field_main_search" name="keyword_field_main_search" value="<?php $custom_search; ?>" />
                    <div class="clearfix"></div>
                </div>
                <?php
            }
            else
            {
                ?>

                <div id="keyword_field_main" style="display: none;">
                    <div class="filter-fileds"><b id="keyword_field_name">Keyword</b></div>
                    <input type="hidden" id="keyword_field_main_search" name="keyword_field_main_search" value="" />
                </div>
            <?php } ?>
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
        <?php
        if (isset($this->session->userdata['custom_search']) && $this->session->userdata['custom_search'] != '')
        {

            $style = "none;";
            $reset = "block;";
        } else
        {
            $style = "block;";
            $reset = "none;";
        }
        ?>
        <div class="filter-fileds" id="limit_field_div" style="display:<?php echo $style; ?>">
            <div><b>Search</b></div>
            <div>
                <input type="text" name="search" id="search" value=""/>
            </div>
        </div>

        <div class="filter-fileds">
            <div class="btn-group" id="limit_field_dropdown" style="display:<?php echo $style; ?>">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <span id="limit_field_text">Limit Search to Field</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown"><a href="#" style="white-space: normal;">Asset Fields <i class="icon-chevron-right" style="float: right;"></i></a>
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

                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"  style="white-space: normal;">Instantiation Fields <i class="icon-chevron-right" style="float: right;"></i></a>
                        <ul class="sub-menu dropdown-menu">
                            <li><a href="javascript://;" onclick="add_custom_token('ID','instantiation_identifier');">ID</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('ID Source','instantiation_source');">Identifier Source</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Dimensions','instantiation_dimension');">Dimensions</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Unit of Measure','unit_of_measure');">Unit of Measure</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Standard','standard');">Standard</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Location','location');">Location</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('File Size','file_size');">File Size</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Duration','actual_duration track_duration');">Duration</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Data Rate','track_data_rate data_rate');">Data Rate</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Tracks','tracks');">Tracks</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Channel Configuration','channel_configuration');">Channel Configuration</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Language','language track_language');">Language</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Alternative Modes','alternative_modes');">Alternative Modes</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Annotation','ins_annotation track_annotation');">Annotation</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Annotation Type','ins_annotation_type');">Annotation Type</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Track Type','track_essence_track_type');">Track Type</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Encoding','track_encoding');">Encoding</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Track Standard','track_standard');">Track Standard</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Frame Rate','track_frame_rate');">Frame Rate</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Playback Speed','track_playback_speed');">Playback Speed</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Sampling Rate','track_sampling_rate');">Sampling Rate</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Bit Depth','track_bit_depth');">Bit Depth</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Frame Size','track_width track_height');">Frame Size</a></li>
                            <li><a href="javascript://;" onclick="add_custom_token('Aspect Ratio','track_aspect_ratio');">Aspect Ratio</a></li>



                        </ul>
                    </li>
                </ul>
            </div>


        </div>
        <div class="filter-fileds" id="limit_btn">
            <div><input type="button"  style="display:<?php echo $style; ?>" id="add_keyword" name="add_keyword" value="Add Keyword" class="btn btn-primary" onclick="add_token('','keyword_field_main');"/></div>
            <div><input type="reset" style="display:<?php echo $reset; ?>" id="reset_search" name="reset_search" value="Reset" class="btn" onclick="resetKeyword();"/></div>
        </div>
        <div class="clearfix"></div>
        <div id="date_range_filter">
            <div class="filter-fileds"><b>Date</b></div>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                    <input type="text" name="date_range" id="date_range" value="" style="width: 187px;"/>
                </div>
            </div>
        </div>
        <?php
        if (count($date_types) > 0)
        {
            ?>
            <div class="filter-fileds">
                <input id="date_type" name="date_type" value="" type="hidden"/>
                <div class="btn-group" id="limit_field_dropdown">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <span id="date_field_text">Date Type</span>
                        
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($date_types as $value)
                        {?>
                            <li><a href="javascript://;" onclick="add_date_token('<?php echo $value->date_type; ?>');"><?php echo $value->date_type; ?></a></li>
                       <?php }
                        ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
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
    $(document).ready(function() {
        $('#date_range').daterangepicker(
        {
            ranges: {
                'Today': ['today', 'today'],
                'Yesterday': ['yesterday', 'yesterday'],
                'Last 7 Days': [Date.today().add({ days: -6 }), 'today'],
                'Last 30 Days': [Date.today().add({ days: -29 }), 'today'],
                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                'Last Month': [Date.today().moveToFirstDayOfMonth().add({ months: -1 }), Date.today().moveToFirstDayOfMonth().add({ days: -1 })]
            },
            opens: 'right',
            format: 'MM/dd/yyyy',
            separator: ' to ',
            startDate: Date.today().add({ days: -29 }),
            endDate: Date.today(),
            minDate: '01/01/2012',
            maxDate: '<?php echo date("mm/dd/YY"); ?>',
            locale: {
                applyLabel: 'Add Date',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ['btn-primary']
        }, 
        function(start, end) {
            //$('#reportrange span').html(start.toString('MM/dd/yyyy') + ' - ' + end.toString('MM/dd/yyyy'));
            facet_search('0');
        }
    );

        //Set the initial state of the picker label
        $('#reportrange span').html(Date.today().add({ days: -29 }).toString('MM/dd/yyyy') + ' to ' + Date.today().toString('MM/dd/yyyy'));

    });
    function add_token(name,type,isRemoved){
        if(type=='keyword_field_main'){
            
            name=$('#search').val();  
            if(isRemoved==1){
                $('#keyword_field_main .btn-img').each(function(){
                    $(this).remove();
                });
                $('#keyword_field_main_search').val('');
                $('#keyword_field_name').html();
                $('#limit_btn').show(); 
                $('#add_keyword').show(); 
                $('#reset_search').hide();
                $('#limit_field_text').html('Limit Search to Field');
                $('#limit_field_dropdown').show();
                $('#search').val('');
                $('#limit_field_div').show();
                customColumnName='';
                customFieldName='All';
                    
            }
            else{
                if($('#search').val()!=''){
                    $('#keyword_field_main .btn-img').each(function(){
                        $(this).remove();
                    });
                    $('#add_keyword').hide(); 
                    $('#reset_search').show();
                    $('#limit_field_dropdown').hide();
                    $('#limit_field_div').hide();
                    var random_id=rand(0,1000365);
                    slugName=make_slug_name(name);
                    var search_id=slugName+random_id;
                    $('#keyword_field_name').html('Keyword: '+customFieldName);
                    $('#'+type).append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" style="float: right;" onclick="remove_token(\''+escape(name)+'\',\''+search_id+'\',\''+type+'\');"></i></div>');
                    $('#'+type).show();
                    var searchString='';
                    if(customColumnName!=''){
                        customColumnName= customColumnName.split(' ');
                        if(customColumnName.length>1){
                            searchString=' @'+customColumnName[0]+' |||'+$('#search').val()+'||| ';
                            searchString +=' @'+customColumnName[1]+' |||'+$('#search').val()+'||| ';
                        }
                        else{
                            searchString=' @'+customColumnName[0]+' |||'+$('#search').val()+'||| ';
                        }
                        
                    }
                    else{
                        searchString+=' |||'+$('#search').val()+'||| ';
                    }
                    
                    $('#keyword_field_main_search').val(searchString);
                        
                }
                else{
                    return false;
                }
            }
        }
        else{
            if(isRemoved!=1){
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
            }
        }
        facet_search('0');
        
    }
    var customFieldName='All';
    var customColumnName='';
    function add_custom_token(fieldName,columnName){
        text=$('#search').val();
        customFieldName=fieldName;
        customColumnName=columnName;
        $('#limit_field_text').html(fieldName);
    }
    function add_date_token(type){
        $('#date_type').val(type);
        $('#date_field_text').html(type);
    }
    function make_slug_name(string){
        string = string.split('/').join('');
        string = string.split('??').join('');
        string = string.split(' ').join('');
        string = string.split('(').join('');
        string = string.split(')').join('');
        string = string.split(',').join('');
        string = string.split('.').join('');
        string = string.split('"').join('');
        string = string.split('\'').join('');
        string = string.split(':').join('');
        string = string.split(';').join('');
        string = string.toLowerCase();
        return string;
    }
    function remove_token(name,id,type)
    {
        if(type=='keyword_field_main'){
            $('#'+type).hide();
            $('#keyword_field_main .btn-img').each(function(){
                $(this).remove();
            });
        }
        $("#"+id).remove();
        if($('#'+type+' div').length<=1){
            $('#'+type).hide();
        }
        add_token(unescape(name),type,1);        
    }
    function resetKeyword(){
        
        $('#keyword_field_main .btn-img').each(function(){
            $(this).remove();
        });
        $('#keyword_field_main_search').val('');
        $('#limit_btn').show(); 
        $('#add_keyword').show(); 
        $('#reset_search').hide();
        $('#limit_field_text').html('Limit Search to Field');
        $('#limit_field_dropdown').show();
        $('#search').val('');
        $('#keyword_field_main').hide();
        $('#limit_field_div').show();
        customColumnName='';
        customFieldName='All';
        facet_search('0');
    }
    function facet_search(page)
    {
        if(typeof(page) == undefined)
        {
            page=0;
        }
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
        $.ajax({
            type: 'POST', 
            url: '<?php echo $facet_search_url ?>/'+page,
            data:$('#form_search').serialize(),
            success: function (result)
            { 
                $('#data_container').html(result); 
<?php
if (!isset($this->session->userdata['current_tab']) || $this->session->userdata['current_tab'] == 'full_table')
{
    ?>
                        updateDataTable();
<?php }
?>
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
            success: function (result)
            { 
                window.location.reload();
            }
        });
    }
    function updateSimpleDataTable()
    {
        var sTable = $('#assets_table').dataTable({
            "sDom": "frtiS",
            'bPaginate':false,
            'bInfo':false,
            'bFilter': false,
            "bSort": false,
            "sScrollY": 400,
            "sScrollX": "100%",
            "bDeferRender": true,
            "bAutoWidth": false
        });
        $.extend( $.fn.dataTableExt.oStdClasses, {
            "sWrapper": "dataTables_wrapper form-inline"
        } );
    }
</script>
