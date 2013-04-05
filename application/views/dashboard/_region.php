
<style>
	.container-map{
		width:460px;
		margin:0 auto;
		height:320px;
		color:#FFF;
		text-align:center;
		font-family:Arial, Helvetica, sans-serif;
		background:url(/images/map.png) no-repeat;
	}
	.container-map h2{
		margin:5px 0 0;
		line-height: 23px;
		font-weight: bold;
		font-size:13pt;
	}
	.container-map h3{
		margin:5px 0 0;
		font-size:9pt;
		color:#000;
	}
	.other{
		position:relative;
		width:78px;
		top:54px;
		margin-left:22px;
	}
	.container-map span{
		font-size:11px;
	}
	.first-asset{
		position:relative;
		width:150px;
		top:96px;
		margin-left:16px;
	}
	.second-asset{
		position:relative;
		width:110px;
		top:25px;
		margin-left:196px;
	}
	.third-asset{
		position:relative;
		width:136px;
		top:100px;
		margin-left:233px;
	}
	.forth-asset{
		position:relative;
		width:84px;
		top:-92px;
		margin-left:377px;
	}
	.fifth-asset{
		position:relative;
		width:78px;
		top:55px;
		margin-left:22px;
	}
</style>
<!--[if lt IE 8]>
<style>
		
        .first-asset{
                margin-left:-275px;
                }
        .second-asset{
                margin-left:45px;
                }
        .third-asset{
                margin-left:135px;
                top:90px;
                }
        .forth-asset{
                margin-left:370px;
                top:-108px;
                }
        .fifth-asset{
                margin-left:-350px;
                top:36px;
                }
        .other{
                margin-left:-350px;
                top:36px;
                }
 
</style>
<![endif]-->

<div style="clear: both;"></div>

<div style="width: 48%;float: left;">
	<div class="dashboard-nav">
		<div>
			REGIONS
		</div>

	</div>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#region_digitized" data-toggle="tab">Total Digitized Assets</a></li>
		<li><a href="#region_hours" data-toggle="tab">Total Hours</a></li>

	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="region_digitized" style=" margin: 0 auto">
			<div class="container-map">
				<div class="first-asset">
					<h2><?php echo number_format($total_region_digitized['west']); ?></h2>
					<span>Assets</span>
				</div><!--end of first-asset-->
				<div class="second-asset">
					<h2><?php echo number_format($total_region_digitized['midwest']); ?></h2>
					<span>Assets</span>
				</div><!--end of first-asset-->
				<div class="third-asset">
					<h2><?php echo number_format($total_region_digitized['south']); ?></h2>
					<span>Assets</span>
				</div><!--end of first-asset-->
				<div class="forth-asset">
					<h2><?php echo number_format($total_region_digitized['northeast']); ?></h2>
					<span>Assets</span>
				</div><!--end of first-asset-->
				<div class="fifth-asset">
					<h2><?php echo number_format($total_region_digitized['other']); ?></h2>
					<span>Assets</span>
				</div><!--end of first-asset-->
				<div class="other">
					<h3>Other</h3>
				</div><!--end of other-->
			</div><!--end of container-map-->
		</div>
		<div class="tab-pane" id="region_hours" style=" margin: 0 auto">
			<div class="container-map" id="">
				<div class="first-asset">
					<h2><?php echo number_format($total_hours_region_digitized['west']); ?></h2>
					<span>Hours</span>
				</div><!--end of first-asset-->
				<div class="second-asset">
					<h2><?php echo number_format($total_hours_region_digitized['midwest']); ?></h2>
					<span>Hours</span>
				</div><!--end of first-asset-->
				<div class="third-asset">
					<h2><?php echo number_format($total_hours_region_digitized['south']); ?></h2>
					<span>Hours</span>
				</div><!--end of first-asset-->
				<div class="forth-asset">
					<h2><?php echo number_format($total_hours_region_digitized['northeast']); ?></h2>
					<span>Hours</span>
				</div><!--end of first-asset-->
				<div class="fifth-asset">
					<h2><?php echo number_format($total_hours_region_digitized['other']); ?></h2>
					<span>Hours</span>
				</div><!--end of first-asset-->
				<div class="other">
					<h3>Other</h3>
				</div><!--end of other-->
			</div><!--end of container-map-->
		</div>
	</div>

</div>

