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
												earliestDate: Date.parse('-100years'), //earliest date allowed 
												latestDate: Date.parse('+15years'), //latest date allowed 
												datepickerOptions: {
																changeMonth: true,
																changeYear: true
												},
												onOpen:function(){ if(inframe){ $(window.parent.document).find('iframe:eq(1)').width(700).height('35em');} }, 
												onClose: function(){ if(inframe){ $(window.parent.document).find('iframe:eq(1)').width('100%').height('5em');} }
								}); 
			 });
</script>


<div>
				<input type="text" value="Choose a Date" id="dateRange" />
</div>