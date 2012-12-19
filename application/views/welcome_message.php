
<?php
//echo link_tag("css/datepicker.css");
echo	link_tag	("css/layout.css");
echo	link_js	('eye.js');
echo	link_js	('utils.js');
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
																				$('#date_range').val( formated.join(' to '));
																}
												});
												var state = false;
												$('#date_range').bind('click', function(){
																$('#widgetCalendar').stop().animate({height: state ? 0 : $('#widgetCalendar div.datepicker').get(0).offsetHeight}, 500);
																state = !state;
																return false;
												});
												$('#widgetCalendar div.datepicker').css('position', 'absolute');
								};
	
								EYE.register(initLayout, 'init');
				})(jQuery)
</script>
fasdfadf
dfadfadf
dfadfadf
dfadfad
<div id="widget">
				


				<div id="date_range_filter">
								<div class="filter-fileds"><b>Date</b></div>
								<div class="controls">
												<div class="input-append">
																<input type="text" name="date_range" id="date_range" value="" style="width: 180px;cursor: default;background-color: white;" readonly="readonly"/>
																<span class="add-on" onclick="$('#date_range').val('');$('#datepicker-calendar').DatePickerSetDate('');"><i class="icon-remove-circle"></i></span>
												</div>
												
								</div>
									<div id="widgetCalendar">
												</div>
				</div>
			
</div>
