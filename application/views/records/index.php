
<div class="row-fluid">
  <div class="span3">
    <div id="search_bar"> <b>
      <h4>Assets</h4>
      </b>
      <div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " ><a style="color: white;" href="<?php echo site_url('records/index') ?>" >All Assets</a></div>
      <div style="padding: 8px;" > <a href="<?php echo site_url('records/index') ?>" >Flagged</a></div>
      <br/>
      <br/>
      <b>
      <h4>FILTER ASSETS</h4>
      </b>
      <div style="height:80px;border:dotted;">ASSET FILTER CRITERIA TK</div>
      <br/>
      <input type="button" name"reset" value="Reset" />
    </div>
  </div>
  <div  class="span9">
    <div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
    <ul class="nav nav-tabs">
      <li ><a href="">View type :</a></li>
      <li id="simple_li" class="active"><a onClick="change_view('simple')">Simple Table</a></li>
      <li id="full_table_li"><a onClick="change_view('full_table')">Full Table</a></li>
      <li id="thumbnails_li"><a onClick="change_view('thumbnails')">Thumbnails</a></li>
    </ul>
    <div style="overflow: auto;height: 600px;" id="simple_view">
    	   <table class="tablesorter table table-bordered" >
          <td style="vertical-align:middle;font-weight:bold"><i class="icon-flag"></i></td>
          <th>AA GUID</th>
          <td style="vertical-align:middle;font-weight:bold">Local ID</td>
          <td style="vertical-align:middle;font-weight:bold">Title</td>
          <td style="vertical-align:middle;font-weight:bold">Description</td>
          <td style="vertical-align:middle;font-weight:bold">Instantiations</td>
      </table>
    </div>
    <div style="overflow: scroll;width: 600px;display:none;" id="full_table_view" >
    	   <table class="tablesorter table table-bordered" >
          <td style="vertical-align:middle;font-weight:bold"><i class="icon-flag"></i></td>
          <th>AA GUID</th>
          <td style="vertical-align:middle;font-weight:bold">Local ID</td>
          <td style="vertical-align:middle;font-weight:bold">Title</td>
          <td style="vertical-align:middle;font-weight:bold">Description</td>
          <td style="vertical-align:middle;font-weight:bold">Instantiations</td>
      </table>
    </div>
    <div style="overflow: auto;height: 600px;display:none;" id="thumbnails_view">
    	  <img src="http://placehold.it/140x140" class="img-rounded">
        <img src="http://placehold.it/140x140" class="img-rounded">
        <img src="http://placehold.it/140x140" class="img-rounded">
        <img src="http://placehold.it/140x140" class="img-rounded">
    </div>
  </div>
</div>
