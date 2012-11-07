<div style="margin: 0px -20px;">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#container" data-toggle="tab">Total Digitized Assets</a></li>
    <li><a href="#scheduled" data-toggle="tab">Total Scheduled Assets</a></li>

  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="container" style="min-width: 400px; height: 550px; margin: 0 auto"></div>
    <div class="tab-pane" id="scheduled" style="min-width: 400px; height: 550px; margin: 0 auto">working on it</div>
  </div>
</div>

<script type="text/javascript">
  
  var chart;
  $(document).ready(function() {
    Highcharts.theme = {
      colors: '#000000',
      chart: {
        plotBackgroundColor: 'whiteSmoke',
        
      },
      xAxis: {
        gridLineWidth: 0,
        labels: {
          style: {
            color: '#000000'
          }
        },
        title: {
          style: {
            color: '#000000',
            font: 'Verdana, sans-serif',
            
          }
        }
      },
      yAxis: {
        labels: {
          style: {
            color: '#000000',
          }
        },
        title: {
          style: {
            color: '#000000',
            font: 'Verdana, sans-serif'
          }
        }
      },
      legend: {
        itemStyle: {
          font: 'Verdana, sans-serif',
          color: '#000000'
        },
        itemHoverStyle: {
          color: 'black'
        },
        itemHiddenStyle: {
          color: '#000000'
        }
      },
      labels: {
        style: {
          color: '#000000'
        }
      }
    };
    var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
    chart = new Highcharts.Chart({
      chart: {
        renderTo: 'container',
        type: 'column',
        //        margin: [ 50, 50, 100, 80]
          
      },
        
      title: {
        text: ''
      },
      credits: {
        enabled: false,
        href: "",
        text: "AMS"
      },
      xAxis: {
        categories: [
          '1/4 inch audio tape',
          '3/4 inch videotape',
          '3/4 inch videotape: U-matic',
          '3/4 inch videotape: U-matic SP',
          '8 mm video',
          '8 mm Hi8 Video',
          'Video8',
          'Betacam',
          'Beta',
          'Betacam SP',
          'Betacam SX',
          'Betamax',
          'Betamax: HB',
          'Betamax: Super',
          '1/8 inch audio cassette',
          'Audio cassette',
          'CD',
          'CD-R',
          'CD-ROM',
          'CD-RW',
          'DAT',
          'Betacam Digital (Digi Beta)',
          'DVCAM',
          'DVCAM: Sony',
          'DVCPRO',
          'DVD data disc',
          'DVD-R',
          'DVD+R',
          'DVD',
          'Hi8',
          'Microcassette',
          'Mini-cassette',
          'Minidisc',
          'MiniDV',
          'DVC',
          'S-VHS',
          'Sony IMX tape',
          'MPEG IMX',
          'VHS',
          'LP Record',
          'EP Record',
          'LP Record (45)',
          'Others'
          
        ],
        gridLineWidth: 0,
        labels: {
          rotation: -90,
          align: 'right',
          style: {
            fontSize: '11px',
            fontFamily: 'Verdana, sans-serif'
          }
        },
        lineWidth: 0,
        tickWidth:0
      },
      yAxis: {
        tickInterval:100,
        gridLineWidth: 0,
        min: 0,
        max:1000,
        title: {
          text: ''
        },
        
      },
      legend: {
        enabled: false
      },
      tooltip: {
        formatter: function() {
          return '<b>'+ this.x +'</b><br/>'+
            Highcharts.numberFormat(this.y, 0)+' Digitized';
        }
      },
      series: [{
          name: 'Total Digitized Assets',
          pointWidth: 10,
          borderWidth: 0,
          
          data: [341, 211, 210, 201, 191, 119, 192, 218, 128,127,
            126, 315, 414, 514, 613, 712, 812, 511,101, 109,
            165,342,262,192,442,143,235,347,312,610,
            712,514,212,192,456,567,765,432,345,123,
            123,456,789],
          dataLabels: {
            enabled: true,
            rotation: 0,
            color: '#000000',
            align: 'center',
            x: 0,
            y: 0,
            style: {
              fontSize: '9px',
              fontFamily: 'Verdana, sans-serif'
            }
          }
        }]
    });
  });
    
  
</script>