<input type="text" value="Choose a Date" id="dateRange" />
 
<script type="text/javascript">	
			$(function(){
				 $('input').daterangepicker({arrows: true, dateFormat: 'M d, yy'}); 
				 //demo only
				 $('input').click(function(){
				 	$(window.parent.document).find('iframe:eq(0)').width(700).height('35em').resizable();
				 });
			 });
		</script>