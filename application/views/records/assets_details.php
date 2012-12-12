<div class="row-fluid">
    <h2>Asset Details: <?php echo $asset_details->title ?></h2>
    <div class="span12 form-row">
       
        <?php $this->load->view('records/_list'); ?>
    </div>
    <div class="span9" style="margin-left: 285px;"> 
            <?php /*?><div class="players">
                AUDIO/VIDEO PLAYER
            </div><?php */?><!--end of players-->
            <div class="span12 button-after-player">
                <button class="btn btn-large"><span class="icon-pencil"></span>Edit Asset</button>
                <button class="btn btn-large"><span class="icon-download-alt"></span>Export Asset</button>
            </div>



            <div class="my-navbar span12">
                <div> Intellectual Content </div>
            </div>

            <div class="span12 form-row">
                <div class="span2 form-label">
                    <label><i class="icon-question-sign"></i>* Organization:</label>
                </div>
                <!--end of span3-->
                <div id="search_bar" class="span10">
                    <div class="disabled-field"> <?php echo $asset_details->organization; ?><!--end of btn_group--> 
                    </div>
                    <!--end of disabled-field--> 
                </div>
                <!--end of span9--> 
            </div>
            <?php if (isset($asset_details->asset_type) && !empty($asset_details->asset_type))
            { ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Asset Type:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                        <?php
                        $asset_types = explode(" | ", $asset_details->asset_type);
                        foreach ($asset_types as $asset_type)
                        {
                            ?>
                            <div class="disabled-field"><?php echo trim($asset_type); ?></div>
                <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php
            if (
                    (isset($asset_details->title) && !empty($asset_details->title)) ||
                    (isset($asset_details->title_type) && !empty($asset_details->title_type)) ||
                    (isset($asset_details->title_ref) && !empty($asset_details->title_ref)) ||
                    (isset($asset_details->title_source) && !empty($asset_details->title_source))
            )
            {
                ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i>* Title:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                        <div class="disabled-field">
                            <?php if ($asset_details->title)
                            { ?>
                                <strong>TITLE:</strong><br/>
                                <p><?php echo $asset_details->title ?></p>
                                <br/>
                            <?php } ?>
                            <?php if (isset($asset_details->title_type) && !empty($asset_details->title_type))
                            { ?>
                                <strong>TITLE TYPE:</strong><br/>
                                <?php echo $asset_details->title_type; ?><br/>
                                <br/>
                            <?php } ?>
                            <?php if (isset($asset_details->title_ref) && !empty($asset_details->title_ref))
                            { ?>
                                <strong>TITLE REF:</strong><br/>
        <?php echo $asset_details->title_ref; ?><br/>
                                <br/>
                <?php } ?>
                <?php if (isset($asset_details->title_source) && !empty($asset_details->title_source))
                { ?>
                                <strong>TITLE SOURCE:</strong><br/>
        <?php echo $asset_details->title_source; ?>
    <?php } ?>
                        </div>
                    </div>
                </div>
<?php } ?>
<?php if (isset($asset_details->description) && !empty($asset_details->description))
{ ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Description:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                        <div class="disabled-field">
                            <p><?php echo $asset_details->description; ?></p>
                        </div>
                    </div>
                </div>
                    <?php } ?>
                    <?php if (isset($asset_genres) && !empty($asset_genres))
                    { ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i>* Asset Genres:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                            <?php foreach ($asset_genres as $asset_genre)
                            { ?>
                            <div class="disabled-field">
                                <?php if (isset($asset_genre->genre) && !empty($asset_genre->genre))
                                { ?>
                                    <strong>Genre:</strong><br/>
                                    <?php echo $asset_genre->genre; ?><br/>
                                    <br/>
                            <?php }
                            if (isset($asset_genre->genre_source) && !empty($asset_genre->genre_source))
                            {
                                ?>
                                    <strong>Genre Source:</strong><br/>
                        <?php echo $asset_genre->genre_source; ?>
                    <?php }
                    if (isset($asset_genre->genre_ref) && !empty($asset_genre->genre_ref))
                    {
                        ?>
                                    <strong>Genre Ref:</strong><br/>
            <?php echo $asset_genre->genre_ref; ?>
                            <?php } ?>
                            </div>
                            <?php } ?>
                    </div>
                </div>
                        <?php } ?>
                        <?php if (isset($asset_creators_roles) && !empty($asset_creators_roles))
                        { ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Creator:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                            <?php foreach ($asset_creators_roles as $asset_creators_role)
                            { ?>
                            <div class="disabled-field">
                                <?php if (isset($asset_creators_role->creator_name) && !empty($asset_creators_role->creator_name))
                                { ?>
                                    <strong>Creator Name:</strong><br/>
                                    <?php echo $asset_creators_role->creator_name; ?><br/>
                                    <br/>
                                <?php }
                                if (isset($asset_creators_role->creator_affiliation) && !empty($asset_creators_role->creator_affiliation))
                                {
                                    ?>
                                    <strong>Creator Affiliation:</strong><br/>
                                    <?php echo $asset_creators_role->creator_affiliation; ?>
                                <?php }
                                if (isset($asset_creators_role->creator_ref) && !empty($asset_creators_role->creator_ref))
                                {
                                    ?>
                                    <strong>Creator Ref:</strong><br/>
            <?php echo $asset_creators_role->creator_ref; ?>
                    <?php } ?>
                    <?php if (isset($asset_creators_role->creator_role) && !empty($asset_creators_role->creator_role))
                    { ?>
                                    <strong>Creator Role:</strong><br/>
            <?php echo $asset_creators_role->creator_role; ?><br/>
                                    <br/>
        <?php }
        if (isset($asset_creators_role->creator_role_source) && !empty($asset_creators_role->creator_role_source))
        {
            ?>
                                    <strong>Creator Role Source:</strong><br/>
                                    <?php echo $asset_creators_role->creator_role_source; ?>
                                <?php }
                                if (isset($asset_creators_role->creator_role_ref) && !empty($asset_creators_role->creator_role_ref))
                                {
                                    ?>
                                    <strong>Creator Role Ref:</strong><br/>
                                    <?php echo $asset_creators_role->creator_role_ref; ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                    </div>
                </div>
                        <?php } ?>
                        <?php if (isset($asset_contributor_roles) && !empty($asset_contributor_roles))
                        { ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Contributor:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                            <?php foreach ($asset_contributor_roles as $asset_contributor_role)
                            { ?>
                            <div class="disabled-field">
                                <?php if (isset($asset_contributor_role->contributor_name) && !empty($asset_contributor_role->contributor_name))
                                { ?>
                                    <strong>Contributor Name:</strong><br/>
                                    <?php echo $asset_contributor_role->contributor_name; ?><br/>
                                    <br/>
                            <?php }
                            if (isset($asset_contributor_role->contributor_affiliation) && !empty($asset_contributor_role->contributor_affiliation))
                            {
                                ?>
                                    <strong>Contributor Affiliation:</strong><br/>
                        <?php echo $asset_contributor_role->contributor_affiliation; ?>
                    <?php }
                    if (isset($asset_contributor_role->contributor_ref) && !empty($asset_contributor_role->contributor_ref))
                    {
                        ?>
                                    <strong>Contributor Ref:</strong><br/>
            <?php echo $asset_contributor_role->contributor_ref; ?>
                            <?php } ?>
                            <?php if (isset($asset_contributor_role->contributor_role) && !empty($asset_contributor_role->contributor_role))
                            { ?>
                                    <strong>Contributor Role:</strong><br/>
                                    <?php echo $asset_contributor_role->contributor_role; ?><br/>
                                    <br/>
                                <?php }
                                if (isset($asset_contributor_role->contributor_role_source) && !empty($asset_contributor_role->contributor_role_source))
                                {
                                    ?>
                                    <strong>Contributor Role Source:</strong><br/>
                                    <?php echo $asset_contributor_role->contributor_role_source; ?>
                                <?php }
                                if (isset($asset_contributor_role->contributor_role_ref) && !empty($asset_contributor_role->contributor_role_ref))
                                {
                                    ?>
                                    <strong>Contributor Role Ref:</strong><br/>
                                    <?php echo $asset_contributor_role->contributor_role_ref; ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                    </div>
                </div>
                        <?php } ?>
                        <?php if (isset($asset_publishers_roles) && !empty($asset_publishers_roles))
                        { ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Publisher:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                        <?php foreach ($asset_publishers_roles as $asset_publishers_role)
                        { ?>
                            <div class="disabled-field">
                    <?php if (isset($asset_publishers_role->publisher) && !empty($asset_publishers_role->publisher))
                    { ?>
                                    <strong>Publisher:</strong><br/>
            <?php echo $asset_publishers_role->publisher; ?><br/>
                                    <br/>
        <?php }
        if (isset($asset_publishers_role->publisher_affiliation) && !empty($asset_publishers_role->publisher_affiliation))
        {
            ?>
                                    <strong>Publisher Affiliation:</strong><br/>
                                    <?php echo $asset_publishers_role->publisher_affiliation; ?>
                                <?php }
                                if (isset($asset_publishers_role->publisher_ref) && !empty($asset_publishers_role->publisher_ref))
                                {
                                    ?>
                                    <strong> Publisher Ref:</strong><br/>
                                    <?php echo $asset_publishers_role->publisher_ref; ?>
                                <?php } ?>
                                <?php if (isset($asset_publishers_role->publisher_role) && !empty($asset_publishers_role->publisher_role))
                                { ?>
                                    <strong>Publisher Role:</strong><br/>
                                <?php echo $asset_publishers_role->publisher_role; ?><br/>
                                    <br/>
        <?php }
        if (isset($asset_publishers_role->publisher_role_source) && !empty($asset_publishers_role->publisher_role_source))
        {
            ?>
                                    <strong> Publisher Role Source:</strong><br/>
            <?php echo $asset_publishers_role->publisher_role_source; ?>
        <?php }
        if (isset($asset_publishers_role->publisher_role_ref) && !empty($asset_publishers_role->publisher_role_ref))
        {
            ?>
                                    <strong>Publisher Role Ref:</strong><br/>
                                <?php echo $asset_publishers_role->publisher_role_ref; ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                    </div>
                </div>
                        <?php } ?>
                        <?php if (isset($asset_dates) && !empty($asset_dates))
                        { ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i>* Asset Date:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                            <?php foreach ($asset_dates as $asset_date)
                            { ?>
                            <div class="disabled-field">
        <?php if (isset($asset_dates->dates) && !empty($asset_dates->dates))
        { ?>
                                    <strong>ASSET DATE:</strong><br/>
                        <?php echo $asset_dates->dates; ?><br/>
                                    <br/>
        <?php }
        if (isset($asset_dates->date_type) && !empty($asset_dates->date_type))
        {
            ?>
                                    <strong>ASSET DATE TYPE:</strong><br/>
                                <?php echo $asset_dates->date_type; ?>
                            <?php } ?>
                            </div>
                            <?php } ?>
                    </div>
                </div>
                        <?php } ?>
                        <?php if (isset($asset_subjects) && !empty($asset_subjects))
                        { ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i>* Subjects:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                            <?php foreach ($asset_subjects as $asset_subject)
                            { ?>
                            <div class="disabled-field">
        <?php if (isset($asset_subject->subject) && !empty($asset_subject->subject))
        { ?>
                                    <strong>Subject:</strong><br/>
                        <?php echo $asset_subject->subject; ?><br/>
                                    <br/>
        <?php }
        if (isset($asset_subject->subject_source) && !empty($asset_subject->subject_source))
        {
            ?>
                                    <strong>Subject Source:</strong><br/>
                                <?php echo $asset_subject->subject_source; ?>
                            <?php }
                            if (isset($asset_subject->subject_ref) && !empty($asset_subject->subject_ref))
                            {
                                ?>
                                    <strong>Subject Ref:</strong><br/>
            <?php echo $asset_subject->subject_ref; ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                    </div>
                </div>
                        <?php
                        }
                        if (isset($asset_coverages) && !empty($asset_coverages))
                        {
                            ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Coveragee:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10"><?php
                        foreach ($asset_coverages as $asset_coverage)
                        {
                            ?>
                            <div class="disabled-field"><?php
                            if (isset($asset_coverage->coverage))
                            {
                                    ?>
                                    <strong>Coverage:</strong><br/><?php echo $asset_coverage->coverage; ?><br/><br/>
                            <?php
                            }
                            if (isset($asset_coverage->coverage_type))
                            {
                                ?>

                                    <strong>Coverage Type:</strong><br/>
                                    <?php echo $asset_coverage->coverage_type; ?><br/><br/><?php }
                                ?>   
                            </div><?php }
                            ?>
                    </div>
                </div>
                        <?php
                        }
                        if (isset($rights_summaries) && !empty($rights_summaries))
                        {
                            ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Rights Summaries:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10"><?php
                            foreach ($rights_summaries as $rights_summarie)
                            {
                                ?>
                            <div class="disabled-field"><?php
                        if (isset($rights_summarie->rights))
                        {
                                    ?>

                                    <strong>Rights:</strong><br/><?php echo $rights_summarie->rights; ?><br/><br/>
                                    <?php
                                }
                                if (isset($rights_summarie->rights_link))
                                {
                                    ?>
                                    <strong>Rights Link:</strong><br/>
                                    <?php echo $rights_summarie->rights_link; ?><br/><br/><?php }
                                ?>   
                            </div><?php }
                            ?>
                    </div>
                </div><?php
                }
                if ((isset($asset_audience_level) && !empty($asset_audience_level)) || (isset($asset_audience_rating) && !empty($asset_audience_rating)))
                {
                            ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Audience:</label>
                    </div>
                    <div id="search_bar" class="span10"><?php
            if (isset($asset_audience_level) && !empty($asset_audience_level))
            {
                foreach ($asset_audience_levels as $asset_audience_level)
                {
                                    ?>
                                <div class="disabled-field"><?php
                        if (isset($asset_audience_level->audience_level) && !empty($asset_audience_level->pubaudience_levellisher))
                        {
                                        ?>
                                        <strong>Audience Level:</strong><br/><?php echo $asset_audience_level->audience_level; ?><br/><br/><?php
                    }
                    if (isset($asset_audience_level->audience_level_source) && !empty($asset_audience_level->audience_level_source))
                    {
                        ?>
                                        <strong>Audience Level Source:</strong><br/>
                                    <?php
                                    echo $asset_audience_level->audience_level_source;
                                }
                                if (isset($asset_audience_level->audience_level_ref) && !empty($asset_audience_level->audience_level_ref))
                                {
                                    ?>
                                        <strong>Audience Level Ref:</strong><br/>
                <?php echo $asset_audience_level->audience_level_ref;
            }
            ?>
                                </div><?php
        }
    }
    if (isset($asset_audience_rating) && !empty($asset_audience_rating))
    {
        foreach ($asset_audience_rating as $asset_audience_rating)
        {
            ?>

                                <div class="disabled-field"><?php
            if (isset($asset_audience_rating->audience_rating) && !empty($asset_audience_rating->audience_rating))
            {
                ?>
                                        <strong>Audience Rating:</strong><br/>
                <?php echo $asset_audience_rating->audience_rating; ?><br/><br/><?php
            }
            if (isset($asset_audience_rating->audience_rating_source) && !empty($asset_audience_rating->audience_rating_source))
            {
                ?>
                                        <strong>Audience Rating Source:</strong><br/><?php
                echo $asset_audience_rating->audience_rating_source;
            }
            if (isset($asset_audience_rating->audience_rating_ref) && !empty($asset_audience_rating->audience_rating_ref))
            {
                ?>
                                        <strong>Audience Rating Ref:</strong><br/><?php
                        echo $asset_audience_rating->audience_rating_ref;
                    }
            ?>
                                </div><?php
                    }
                }
    ?>
                    </div>
                </div><?php
                    }
                    if (isset($annotations) && !empty($annotations))
                    {
                        ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i> Annotation:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10"><?php
                        foreach ($annotations as $annotation)
                        {
                            ?>
                            <div class="disabled-field"><?php
                            if (isset($rights_summarie->rights))
                            {
                                ?>

                                    <strong>Rights:</strong><br/><?php echo $rights_summarie->rights; ?><br/><br/>
            <?php
        }
        if (isset($rights_summarie->rights_link))
        {
            ?>
                                    <strong>Rights Link:</strong><br/>
            <?php echo $rights_summarie->rights_link; ?><br/><br/><?php }
        ?>   
                            </div><?php }
    ?>
                    </div>
                </div><?php }
?>











<?php
if (
        (isset($asset_details->local_identifier) && !empty($asset_details->local_identifier)) ||
        (isset($asset_details->local_identifier_source) && !empty($asset_details->local_identifier_source)) ||
        (isset($asset_details->local_identifier_ref) && !empty($asset_details->local_identifier_ref))
)
{
    ?>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i>* Local ID:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                        <div class="disabled-field">
    <?php if (isset($asset_details->local_identifier) && !empty($asset_details->local_identifier))
    { ?>
                                <strong>LOCAL ID:</strong><br/>
        <?php echo $asset_details->local_identifier ?><br/>
                                <br/>
    <?php } ?>
    <?php if (isset($asset_details->local_identifier_source) && !empty($asset_details->local_identifier_source))
    { ?>
                                <strong>LOCAL ID REF:</strong> <br/>
        <?php echo $asset_details->local_identifier_source; ?><br/>
                                <br/>
    <?php } ?>
    <?php if (isset($asset_details->local_identifier_ref) && !empty($asset_details->local_identifier_ref))
    { ?>
                                <strong>LOCAL SOURCE:</strong><br/>
        <?php echo $asset_details->local_identifier_ref; ?>
    <?php } ?>
                        </div>
                    </div>
                </div>
<?php } ?>
            <br clear="all">
<?php if (isset($asset_details->guid_identifier) && !empty($asset_details->guid_identifier))
{ ?>
                <div class="my-navbar">
                    <div>Organiztion</div>
                </div>
                <div class="span12 form-row">
                    <div class="span2 form-label">
                        <label><i class="icon-question-sign"></i>American Archive GUID:</label>
                    </div>
                    <!--end of span3-->
                    <div id="search_bar" class="span10">
                        <div class="disabled-field"> <?php echo $asset_details->guid_identifier ?><!--end of btn_group--> 
                        </div>
                        <!--end of disabled-field--> 
                    </div>
                    <!--end of span9--> 
                </div>
<?php } ?>
        </div>
    </div>
</div>