<div class="row-fluid">
    <h2>Instantiation Details: <?php	echo	$asset_details->title	?></h2>
   

								<?php	$this->load->view('records/_list');	?>
   
    <div class="span9"  style="margin-left: 285px;">
								<div class="span9 button-after-player">
            <button class="btn btn-large"><span class="icon-pencil"></span>Edit Instantiation</button>
            <button class="btn btn-large"><span class="icon-download-alt"></span>Export Instantiation</button>
        </div>



        <div class="my-navbar span9">
            <div>Instantiation Details</div>
        </div>

        <div class="span9 form-row">
            <div class="span2 form-label">
                <label><i class="icon-question-sign"></i>* Organization:</label>
            </div>
            <!--end of span3-->
            <div id="search_bar" class="span10">
                <div class="disabled-field"> <?php	echo	$asset_details->organization;	?><!--end of btn_group--> 
                </div>
                <!--end of disabled-field--> 
            </div>
            <!--end of span9--> 
        </div>
        <div class="span9 form-row">
            <div class="span2 form-label">
                <label><i class="icon-question-sign"></i>* Instantiation:</label>
            </div>
            <!--end of span3-->
            <div id="search_bar" class="span10">
                <div class="disabled-field">
																				<?php
																				if($instantiation_detail->instantiation_identifier)
																				{
																								?>
																								<strong>Instantiation ID:</strong><br/>
																								<p><?php	echo	$instantiation_detail->instantiation_identifier;	?></p>
																								<br/>
																				<?php	}	?>
																				<?php
																				if($instantiation_detail->instantiation_source)
																				{
																								?>
																								<strong>Instantiation ID Source:</strong><br/>
																								<p><?php	echo	$instantiation_detail->instantiation_source;	?></p>
																								<br/>
																				<?php	}	?>

                </div>
            </div>

        </div>
								<?php
								if($instantiation_detail->media_type)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>* Media Type:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">

																								<strong>Media Type:</strong><br/>
																								<p><?php	echo	$instantiation_detail->media_type;	?></p>
																								<br/>


																				</div>
																</div>

												</div>
								<?php	}	?>


								<?php
								if($instantiation_detail->format_type	&&	$instantiation_detail->format_type	!=	'')
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>Format:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">
																								<?php
																								if($instantiation_detail->format_type)
																								{
																												?>
																												<strong>Format Type:</strong><br/>
																												<p><?php	echo	$instantiation_detail->format_type;	?></p>
																												<br/>
																								<?php	}	?>
																								<?php
																								if($instantiation_detail->format_name)
																								{
																												?>
																												<strong>Format Name:</strong><br/>
																												<p><?php	echo	$instantiation_detail->format_name;	?></p>
																												<br/>
																								<?php	}	?>

																				</div>
																</div>

												</div>
								<?php	}	?>

								<?php
								if($instantiation_detail->generation)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>Generation:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">

																								<strong>Generation:</strong><br/>
																								<p><?php	echo	$instantiation_detail->generation;	?></p>
																								<br/>


																				</div>
																</div>

												</div>
								<?php	}	?>

								<?php
								if($instantiation_detail->actual_duration	||	$instantiation_detail->projected_duration)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>Duration:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">
																								<?php
																								if($instantiation_detail->actual_duration)
																								{
																												?>
																												<strong>Actual Duration:</strong><br/>
																												<p><?php	echo	$instantiation_detail->actual_duration;	?></p>
																												<br/>
																								<?php	}	?>
																								<?php
																								if($instantiation_detail->projected_duration)
																								{
																												?>
																												<strong>Projected Duration:</strong><br/>
																												<p><?php	echo	$instantiation_detail->projected_duration;	?></p>
																												<br/>
																								<?php	}	?>
																				</div>

																</div>
												</div>
								<?php	}	?>
								<?php
								if($instantiation_detail->dates	||	$instantiation_detail->date_type)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>Date:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">
																								<?php
																								if($instantiation_detail->dates)
																								{
																												?>
																												<strong>Date:</strong><br/>
																												<p><?php	echo	date('Y-m-d',	$instantiation_detail->dates);	?></p>
																												<br/>
																								<?php	}	?>
																								<?php
																								if($instantiation_detail->date_type)
																								{
																												?>
																												<strong>Date Type:</strong><br/>
																												<p><?php	echo	$instantiation_detail->date_type;	?></p>
																												<br/>
																								<?php	}	?>
																				</div>

																</div>
												</div>
								<?php	}	?>
								<?php
								if($instantiation_detail->location)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>* Location:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">

																								<strong>Location:</strong><br/>
																								<p><?php	echo	$instantiation_detail->location;	?></p>
																								<br/>


																				</div>
																</div>
												</div>
								<?php	}	?>

								<?php
								if($instantiation_detail->file_size	||	$instantiation_detail->file_size_unit_of_measure)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>File Size:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">
																								<?php
																								if($instantiation_detail->file_size)
																								{
																												?>
																												<strong>File Size:</strong><br/>
																												<p><?php	echo	$instantiation_detail->file_size;	?></p>
																												<br/>
																								<?php	}	?>
																								<?php
																								if($instantiation_detail->file_size_unit_of_measure)
																								{
																												?>
																												<strong>Unit of Measure:</strong><br/>
																												<p><?php	echo	$instantiation_detail->file_size_unit_of_measure;	?></p>
																												<br/>
																								<?php	}	?>
																				</div>

																</div>
												</div>
								<?php	}	?>

								<?php
								if($instantiation_detail->color)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>Color:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">

																								<strong>Color:</strong><br/>
																								<p><?php	echo	$instantiation_detail->color;	?></p>
																								<br/>


																				</div>
																</div>
												</div>
								<?php	}	?>
								<?php
								if($instantiation_detail->language)
								{
												?>
												<div class="span9 form-row">
																<div class="span2 form-label">
																				<label><i class="icon-question-sign"></i>Language:</label>
																</div>
																<!--end of span3-->
																<div id="search_bar" class="span10">
																				<div class="disabled-field">

																								<strong>Language:</strong><br/>
																								<p><?php	echo	$instantiation_detail->language;	?></p>
																								<br/>


																				</div>
																</div>
												</div>
								<?php	}	?>
				</div>
</div>

