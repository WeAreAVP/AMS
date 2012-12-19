
<?php
 //echo link_tag("css/datepicker.css");
	 echo link_tag("css/layout.css");
echo	link_js	('eye.js');
//echo	link_js	('utils.js');
?>
<script>
				(function($){
								var initLayout = function() {
												var hash = window.location.hash.replace('#', '');
											
														$('#clearSelection').bind('click', function(){
																$('#date3').DatePickerClear();
																return false;
												});
										
												$('#widgetCalendar').DatePicker({
																flat: true,
																format: 'd B, Y',
																date: [new Date('11-12-2012'), new Date('16-12-2012')],
																calendars: 3,
																mode: 'range',
																starts: 1,
																onChange: function(formated) {
																				$('#widgetField span').get(0).innerHTML = formated.join(' to ');
																}
												});
												var state = false;
												$('#widgetField>a').bind('click', function(){
																$('#widgetCalendar').stop().animate({height: state ? 0 : $('#widgetCalendar div.datepicker').get(0).offsetHeight}, 500);
																state = !state;
																return false;
												});
												$('#widgetCalendar div.datepicker').css('position', 'absolute');
								};
	
								EYE.register(initLayout, 'init');
				})(jQuery)
</script>
<div id="widget">
				<div id="widgetField">
								<span>28 July, 2008 to 31 July, 2008</span>
								<a href="#">Select date range</a>
				</div>
				<div id="widgetCalendar">
				</div>
</div>