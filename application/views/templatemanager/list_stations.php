<div class="row-fluid">
<div class="span3"> </div>
<div class="span9">
  <div style="overflow: scroll;height: 600px;" >
    <table class="tablesorter table table-bordered" id="station_table">
      <?php
			
			 if (isset($stations) && count($stations) > 0) {
      ?>
      <thead>
        <tr>
        	<td><span style="float:left;min-width:25px;"><input type='checkbox' name='all' value='' id='check_all'  class="check-all" onclick='javascript:checkAll();' /></span></td>
          <th><span style="float:left;min-width:80px;">Station Name</span></th>
          <th><span style="float:left;min-width:90px;">Contact Name</span></th>
          <th><span style="float:left;min-width:80px;">Contact Title</span></th>
          <th><span style="float:left;min-width:30px;">Type</span></th>
          <td><span style="float:left;min-width:30px;">DSD</span></td>
          <td><span style="float:left;min-width:30px;">DED</span></td>
        </tr>
      </thead>
      <tbody>
        <?php
      	foreach ($stations as $data)
				{?>
        <tr>
        	<td><input style='margin-left:15px;' type='checkbox' name='station[]' value='<?php echo $data->id; ?>'  class='checkboxes'/></td>
          <td><?php echo $data->station_name; ?></td>
          <td><?php echo $data->contact_name; ?></td>
          <td><?php echo $data->contact_title; ?></td>
          <td><?php
							if ($data->type == 0)
									echo 'Radio';
							else if ($data->type == 1)
									echo 'TV';
							else if ($data->type == 2)
									echo 'Joint';
							else
									echo 'Unknown';
							?></td>
          <td id="start_date_<?php echo $data->id; ?>"><?php 
					if (empty($data->start_date))
					{?>
            No DSD <?php
          }
					else
					{
						echo $data->start_date;
					}?>
          </td>
          <td id="end_date_<?php echo $data->id; ?>"><?php 
					if (empty($data->end_date))
					{?>
            No DED <?php
          }
					else
					{
						echo $data->end_date;
					}?>
          </td>
        </tr>
        <?php
        }
			}
			else
			{?>
        <tr>
          <td colspan="11" style="text-align: center;"><b>No Station Found.</b></td>
        </tr>
        <?php 
			} ?>
      </tbody>
    </table>
  </div>
  <?php
			
			 if (isset($stations) && count($stations) > 0 && isset($templates) && count($templates)>0) {
      ?>
   			<div class="btn-toolbar" style="margin:5px 0px;">
   				<div class="btn-group">
                <button data-toggle="dropdown" class="btn dropdown-toggle">Messages<span class="caret"></span></button>
                
                <ul class="dropdown-menu">
                <?php 
                foreach($templates as $row){?>
                  <li><a href="javascript:;" onClick="send_message('<?php echo $row->system_id?>');"><?php echo $row->system_id?></a></li>
                <?php } ?>
                </ul>
              </div>
	  
   </div>
   <?php }?>
</div>
<script type="text/javascript">
$('.dropdown-toggle').dropdown()
function send_message(template_id)
{
}
		
</script> 
