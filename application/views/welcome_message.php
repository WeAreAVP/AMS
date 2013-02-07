<?php 

	echo	link_js('date.js');
	echo	link_js('daterangepicker.jQuery.js');
		echo	link_tag("css/ui.daterangepicker.css");

?>

<script type="text/javascript">
				$(function(){
								if($(window.parent.document).find('iframe').size()){
												var inframe = true;
								}
								$('#dateRange').daterangepicker({
											 
												posX: null,
												posY: null,
												arrows: true, 
												dateFormat: 'M d, yy',
												rangeSplitter: 'to',
										 	
												datepickerOptions: {
																changeMonth: true,
																changeYear: true,
																yearRange: '-1000:+15'
																
												},
												onOpen:function(){ if(inframe){ $(window.parent.document).find('iframe:eq(1)').width(700).height('35em');} }, 
												onClose: function(){ if(inframe){ $(window.parent.document).find('iframe:eq(1)').width('100%').height('5em');} }
								}); 
			 });
</script>


<div>
				<input type="text" value="Choose a Date" id="dateRange" />
</div>