
<div id="search_bar"> 
    <form name="facet_search" id="form_search" method="post">
    <input type="hidden" name="current_tab" id="current_tab" value="<?php echo isset($this->session->userdata['current_tab'])?$this->session->userdata['current_tab']:''?>"  />
        <b>
            <h4>Filter</h4>
        </b>
        <div id="tokens">
            <div id="keyword-field_main" style="display: none;">
                <div class="filter-fileds"><b>Keyword</b></div>
                <div class="btn-img" id="" ><span class="search_keys">abc</span><i class="icon-remove-sign" onclick=""></i></div>
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
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript://">
                    Limit Search to Field
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown"><a href="javascript://">Asset Fields <i class="icon-chevron-right" style="float: right;"></i></a>
                        <ul class="sub-menu dropdown-menu">
                            <li>Title</li>
                            <li><a href="#">Subject</a></li>
                            <li><a href="#">Coverage</a></li>
                            <li><a href="#">Genre</a></li>
                            <li><a href="#">Publisher</a></li>
                            <li><a href="#">Description</a></li>
                            <li><a href="#">Creator Name</a></li>
                            <li><a href="#">Creator Affiliation</a></li>
                            <li><a href="#">Contributor Name</a></li>
                            <li><a href="#">Contributor Affiliation</a></li>
                            <li><a href="#">Rights Summaries</a></li>
                            <li><a href="#">Annotations</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#">Instantiation Fields <i class="icon-chevron-right" style="float: right;"></i></a>
                        <ul class="sub-menu dropdown-menu">
                            <li><a href="#">TBD</a></li>

                        </ul>
                    </li>
                </ul>
            </div>


        </div>
        <div class="filter-fileds">
            <div><input type="button" name="reset" value="Add Keyword" class="btn btn-primary"/></div>
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
            console.log(my_search_words);
            
        }
        facet_search('{"page":0}');
        
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
            url: '<?php echo site_url('records/set_current_tab')?>/'+id,
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
