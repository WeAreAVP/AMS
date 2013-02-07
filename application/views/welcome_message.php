<script type="text/javascript">
			$(function(){
					$('#rangeA').daterangepicker();
					$('#rangeBa, #rangeBb').daterangepicker();
					$('#rangeC').daterangepicker({arrows: true});
					$('#rangeD').daterangepicker();
					$('#rangeE').daterangepicker({constrainDates: true});
			 });
		</script>
		
		
		<div>
			<input type="text" value="4/23/99" id="rangeA" />
		</div>

		<h2>2 inputs Rangepicker</h2>
		<div>
			<input type="text" value="4/23/99" id="rangeBa" />
			<input type="text" value="4/23/99" id="rangeBb" />
		</div>

		<h2>Rangepicker with arrows</h2>
		<div>
			<input type="text" value="4/23/99" id="rangeC" />
		</div>

		<h2>Rangepicker opening to the right</h2>
		<div style="float: right;">
			<input type="text" value="4/23/99" id="rangeD" />
		</div>

		<h2>Rangepicker with contraints</h2>
		<div>
			<input type="text" value="4/23/99" id="rangeE" />
		</div>