<div class="row">
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-star"></i><span>Quality Control Issues</span></div>

    </div>
    <div class="report-tab-detail">Details QC issues (Visual Inspection, cleaning, migration QC, tape failure, unresponsive station) related to stations</div>
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-plane"></i><span>Ship Dates</span></div>
    </div>
    <div class="report-tab-detail">Details all Ship Dates</div>
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab">

        <i class="icon-ok-sign"></i><span>Completed Migration</span></div>
    </div>
    <div class="report-tab-detail">All Stations that have completed migration ( All items returned )</div> 

  </div>
</div>

<!-- New Row -->
<div class="row">
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-th-large"></i><span>Total Digitization Size</span></div>
    </div>
    <div class="report-tab-detail">Details Total Hours and File Size for all Stations</div> 
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-pencil"></i><span>Notes Report</span></div>
    </div>
    <div class="report-tab-detail">Display all Notes related to a particular Station</div> 
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-th-list"></i><span>Nominated List</span></div>
    </div>
    <div class="report-tab-detail">Display all Notes related to a particular Station</div> 
  </div>
</div>

<!-- New Row -->
<div class="row">
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-exclamation-sign"></i><span>Nominated Priority</span></div>
    </div>
    <div class="report-tab-detail">The nomination priority of media objects that have been digitized</div> 
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-volume-up"></i><span>Radio Assets Scheduled</span></div>
    </div>
    <div class="report-tab-detail">Radio assets scheduled to be digitized</div> 
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-volume-up"></i><span>Radio Assets Digitized</span></div>
    </div>
    <div class="report-tab-detail">Radio assets already digitized</div> 
  </div>
</div>

<!-- New Row -->
<div class="row">
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-home"></i><span>Station Digitization</span></div>
    </div>
    <div class="report-tab-detail">List digitized material for a station</div> 
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-facetime-video"></i><span>TV Assets Scheduled</span></div>
    </div>
    <div class="report-tab-detail">TV assets scheduled to be digitized</div> 
  </div>
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-facetime-video"></i><span>TV Assets Digitized</span></div>
    </div>
    <div class="report-tab-detail">TV assets already digitized</div> 
  </div>
</div>


<!-- New Row -->
<div class="row">
  <div class="span4 report-main">
    <div class="navbar-inner">
      <div class="report-tab"><i class="icon-repeat"></i><span>Updated Durations</span></div>
    </div>
    <div class="report-tab-detail">Updated Durations for all Media Items grouped by Station</div> 
  </div>
</div>
<?php $this->load->view('reports/_report_popup'); ?>

