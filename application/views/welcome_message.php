<script type="text/javascript">
			$(function(){
				if($(window.parent.document).find('iframe').size()){
					var inframe = true;
				}
				 $('input').daterangepicker({
				 	presetRanges: [
						{text: 'Ad Campaign', dateStart: 'Today', dateEnd: '03/07/09' },
						{text: 'Spring Vacation', dateStart: '03/04/09', dateEnd: '03/08/09' },
						{text: 'Office Closed', dateStart: '04/04/09', dateEnd: '04/08/09' }
					], 
					posX: null,
				 	posY: null,
				 	arrows: true, 
				 	dateFormat: 'M d, yy',
				 	rangeSplitter: 'to',
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