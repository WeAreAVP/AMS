
<div class="row-fluid">
  <div class="span3">
    <div id="search_bar"> <b>
      <h4>Assets</h4>
      </b>
      <div style="padding: 8px;" ><a  href="<?php echo site_url('records/index') ?>" >All Assets</a></div>
      <div style="padding: 8px;background: none repeat scroll 0% 0% rgb(0, 152, 214); " > <a  style="color: white;" href="<?php echo site_url('records/flagged') ?>" >Flagged</a></div>
      <br/>
      <br/>
      <b>
      <h4>FILTER ASSETS</h4>
      </b>
      <div style="height:80px;border:dotted;">Flagged FILTER CRITERIA TK</div>
      <br/>
      <input type="button" name"reset" value="Reset" />
    </div>
  </div>
  <div  class="span9">
  	
    <div class="alert" style="margin-bottom: 0px; margin-top: 0px;display: none;" id="success_message"></div>
   
    
    <ul class="nav nav-tabs">
      <li ><a href="javascript:;" style="color:#000;cursor:default;">View type :</a></li>
      <li id="simple_li" class="active"><a href="javascript:;" onClick="change_view('simple')">Simple Table</a></li>
      <li id="full_table_li"><a href="javascript:;" onClick="change_view('full_table')">Full Table</a></li>
      <li id="thumbnails_li"><a href="javascript:;" onClick="change_view('thumbnails')">Thumbnails</a></li>
    </ul>
     <div><input type="button" name="clear_all_flags" value="Clear All Flags" /><br/></div>
    <div style="overflow: auto;height: 400px;" id="simple_view">
      <table class="tablesorter table table-bordered" >
        
          <td style="vertical-align:middle;font-weight:bold"><i class="icon-flag "></i></td>
          <th>AA GUID</th>
          <td style="vertical-align:middle;font-weight:bold">Local ID</td>
          <td style="vertical-align:middle;font-weight:bold">Title</td>
          <td style="vertical-align:middle;font-weight:bold">Description</td>
          <td style="vertical-align:middle;font-weight:bold">Instantiations</td>
        <tbody>
          <tr>
             <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="flag"></i></td>
            <td>cpb-aacip/109-000000b9</td>
            <td>Set 1 Box 64 Tape 22 | !b99758-96ab-48ef-ba66-f71ee48d0245</td>
            <td><p>10:05-10:20 BAKER SPRING RESEARCH
                FELLOW AND NATIONAL SECURITY AT THE
                HERITAGE FOUNDATION http://
                www.heritage.org 202-608-6112, CT. JOE
                DOUGHERTY 202 546-4400 FAR OUT
                RUMSFELD PROPOSES TO | On the Line</p></td>
            <td><p>Lorem ipsum dolor sit
              amet, consectetur
              adipiscing elit. Integer
              tincidunt, odio id ultrices
              ultrices, eros magna
              condimentum turpis, ac
              suscipit sem ligula nec
              augue...</p></td>
            <td><p>Instn. 1 (Betacam
              SP)
              Instn. 2 (VHS)
              Instn. 3 (Cassette)</p></td>
          </tr>
           <tr>
             <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="flag"></i></td>
             <td>AA GUID</td>
            <td>Local ID</td>
            <td>Title</td>
            <td><p>Description</p></td>
            <td><p>Instantiations</p></td>
            
          </tr>
        </tbody>
      </table>
    </div>
    <div style="overflow: scroll;display:none;" id="full_table_view" >
      <table class="tablesorter table table-bordered" >
        
          <td style="vertical-align:middle;font-weight:bold"><i class="icon-flag "></i></td>
          <th>AA GUID</th>
          <td style="vertical-align:middle;font-weight:bold">Local ID</td>
          <td style="vertical-align:middle;font-weight:bold">Title</td>
          <td style="vertical-align:middle;font-weight:bold">Description</td>
          <td style="vertical-align:middle;font-weight:bold">Instantiations</td>
        <tbody>
          <tr>
             <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="flag"></i></td>
            <td>cpb-aacip/109-000000b9</td>
            <td>Set 1 Box 64 Tape 22 | !b99758-96ab-48ef-ba66-f71ee48d0245</td>
            <td><p>10:05-10:20 BAKER SPRING RESEARCH
                FELLOW AND NATIONAL SECURITY AT THE
                HERITAGE FOUNDATION http://
                www.heritage.org 202-608-6112, CT. JOE
                DOUGHERTY 202 546-4400 FAR OUT
                RUMSFELD PROPOSES TO | On the Line</p></td>
            <td><p>Lorem ipsum dolor sit
              amet, consectetur
              adipiscing elit. Integer
              tincidunt, odio id ultrices
              ultrices, eros magna
              condimentum turpis, ac
              suscipit sem ligula nec
              augue...</p></td>
            <td><p>Instn. 1 (Betacam
              SP)
              Instn. 2 (VHS)
              Instn. 3 (Cassette)</p></td>
          </tr>
           <tr>
             <td style="vertical-align:middle;font-weight:bold"><i style="margin:0px" class="flag"></i></td>
             <td>AA GUID</td>
            <td>Local ID</td>
            <td>Title</td>
            <td><p>Description</p></td>
            <td><p>Instantiations</p></td>
            
          </tr>
        </tbody>
      </table>
    </div>
    <div style="overflow: auto;display:none;" id="thumbnails_view">  <div class="span3 title">
        <div class="flag"></div>
        <img width="250px"  src="http://placehold.it/140x140" alt="" />
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
        <img width="250px"  src="http://placehold.it/140x140" alt="" />
        <h4>Title</h4>
        <p>Title of the Asset</p>
</div>

  		<div class="span3 title">
       <div class="flag"></div>
        <img width="250px"  src="http://placehold.it/140x140" alt="" />
        <h4>Title</h4>
        <p>Title of the Asset</p></div>
  		<div class="span3 title">
       <div class="flag"></div>
        <img width="250px" src="http://placehold.it/140x140" alt="" />
        <h4>Title</h4>
        <p>Title of the Asset</p></div>
        </div>
  </div>
</div>
<script>
function change_view(id)
{
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
