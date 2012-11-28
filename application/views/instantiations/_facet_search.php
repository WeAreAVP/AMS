<div id="search_bar"> 
    <form name="facet_search_form" id="form_search_instantiation" method="post">
        <b>
            <h4>Filter</h4>
        </b>
        <div class="filter-fileds">
            <div><b>Search</b></div>
            <div>
                <input type="text" name="search" id="search" value=""/>
            </div>
        </div>
        <div class="filter-fileds">
            <div><input type="button" name="reset" value="Add Keyword" class="btn btn-primary"/></div>
        </div>
        <!-- Organization  Start      -->
        <div class="filter-fileds">
            <b>Organziation</b>
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
                    <div><a href="#"><?php echo $value->station_name; ?></a></div>
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
                            <li><a href="#"><?php echo $value->station_name; ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!-- Organization  End      -->
        <!--  Nomination Status Start      -->
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
                    <div><a href="#"><?php echo $value->status; ?></a></div>
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
                            <li><a href="#"><?php echo $value->status; ?></a></li>  
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

        <!--  Nomination Status End      -->
        <!--  Media Type Start      -->
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
                    <div><a href="#"><?php echo $value->media_type; ?></a></div>
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
                            <li><a href="#"><?php echo $value->media_type; ?></a></li>  
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
        <!--  Media Type End      -->
        <!--  Physical Format Start      -->
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
                    <div><a href="#"><?php echo $value->format_name; ?></a></div>
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
                            <li><a href="#"><?php echo $value->format_name; ?></a></li>  
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
        <!-- Physical Format End      -->
        <!--  Digital Format Start      -->
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
                    <div><a href="#"><?php echo $value->format_name; ?></a></div>
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
                            <li><a href="#"><?php echo $value->format_name; ?></a></li>  
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
        <!-- Digital Format End      -->
        <!--  Generation Start      -->
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
                    <div><a href="#"><?php echo $value->generation; ?></a></div>
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
                            <li><a href="#"><?php echo $value->generation; ?></a></li>  
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
        <!-- Generation End      -->
        <!--  File Size Start      -->
        <?php if (count($file_size) > 0)
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
                        <div><a href="#"><?php echo $value->file_size; ?></a></div>
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
                                <li><a href="#"><?php echo $value->file_size; ?></a></li>  
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
    </form>
</div>

