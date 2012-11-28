
<div id="search_bar"> 
    <form name="facet_search_form" id="form_search_instantiation" method="post">
        <b>
            <h4>Filter</h4>
        </b>
        <div id="tokens">
            <div id="keyword-field_main" style="display: none;">
                <div class="filter-fileds"><b>Keyword</b></div>
                <div class="btn-img" id="" ><span class="search_keys">abc</span><i class="icon-remove-sign" onclick=""></i></div>
            </div>
            <div class="clearfix"></div>
            <div id="organization_main" style="display: none;">
                <div class="filter-fileds"><b>Organization</b></div>
                <input type="hidden" id="organization_main_search" name="organization_main_search" value="<?php echo $searched['organization_main_search'] ?>"/>
            </div>
            <div class="clearfix"></div>
            <div id="nomination_status_main" style="display: none;">
                <div class="filter-fileds"><b>Nomination Status</b></div>
                <input type="hidden" id="nomination_status_main_search" name="nomination_status_main_search"/>
            </div>
            <div class="clearfix"></div>
            <div id="media_type_main" style="display: none;">
                <div class="filter-fileds"><b>Media Type</b></div>
                <input type="hidden" id="media_type_main_search" name="media_type_main_search"/>
            </div>
            <div class="clearfix"></div>
            <div id="physical_format_main" style="display: none;">
                <div class="filter-fileds"><b>Physical Format</b></div>
                <input type="hidden" id="physical_format_main_search" name="physical_format_main_search"/>

            </div>
            <div class="clearfix"></div>
            <div id="digital_format_main" style="display: none;">
                <div class="filter-fileds"><b>Digital Format</b></div>
                <input type="hidden" id="digital_format_main_search" name="digital_format_main_search"/>

            </div>
            <div class="clearfix"></div>
            <div id="generation_main" style="display: none;">
                <div class="filter-fileds"><b>Generation</b></div>
                <input type="hidden" id="generation_main_search" name="generation_main_search"/>

            </div>
            <div class="clearfix"></div>
            <div id="file_size_main" style="display: none;">
                <div class="filter-fileds"><b>File Size</b></div>
                <input type="hidden" id="file_size_main_search" name="file_size_main_search"/>

            </div>
            <div class="clearfix"></div>
            <div id="event_type_main" style="display: none;">
                <div class="filter-fileds"><b>Event Type</b></div>
                <input type="hidden" id="event_type_main_search" name="event_type_main_search"/>

            </div>
            <div class="clearfix"></div>
            <div id="event_outcome_main" style="display: none;">
                <div class="filter-fileds"><b>Event Outcome</b></div>
                <input type="hidden" id="event_outcome_main_search" name="event_outcome_main_search"/>
            </div>
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
            <div class="dropdown">
                <a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
                    Limit Search to Field
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li class="dropdown-submenu">
                        <a tabindex="-1" href="#">Asset Fields</a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li tabindex="-1">1</li>
                            <li tabindex="-1">2</li>
                        </ul>
                    </li>
                    <!--                    <li class="dropdown-submenu">
                                            <a tabindex="-1" href="#">Instantiation Fields</a>
                                            <ul class="dropdown-menu">
                                               <li tabindex="-1">1</li>
                                                <li tabindex="-1">2</li>
                                            </ul>
                                        </li>-->
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
    function add_token(name,type){
        var my_search_words;
        $('#'+type+'_search').val('');
        var random_id=rand(0,1000365);
        slugName=make_slug_name(name);
        var search_id=slugName+random_id;
        $('#'+type).append('<div class="btn-img" id="'+search_id+'" ><span class="search_keys">'+name+'</span><i class="icon-remove-sign" onclick="remove_token(\''+search_id+'\',\''+type+'\')"></i></div>');
        $('#'+type).show();
        $("#"+type+" .search_keys").each(function(index) {
            if(index==0)
                my_search_words=$(this).text();
            else
                my_search_words+=','+$(this).text();
            
        });
        if(my_search_words!='' && typeof(my_search_words)!=undefined)
        {
            $('#'+type+'_search').val(my_search_words);
            
        }
        
        
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
    function remove_token(id,type)
    {
        $("#"+id).remove();
        if($('#'+type+' div').length<=1){
            $('#'+type).hide();
        }
        
    }
</script>
