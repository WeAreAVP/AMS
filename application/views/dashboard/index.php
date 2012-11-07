<div style="margin: 0px -20px;">

  <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
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
            font: 'Verdana, sans-serif'
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
          'Guangzhou',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Shenzhen',
          'Istanbul'
        ],
        gridLineWidth: 0,
        labels: {
          rotation: -90,
          align: 'right',
          style: {
            fontSize: '13px',
            fontFamily: 'Verdana, sans-serif'
          }
        },
        lineWidth: 0,
        tickWidth:0
      },
      yAxis: {
        gridLineWidth: 0,
        min: 0,
        title: {
          text: ''
        }
      },
      legend: {
        enabled: false
      },
      tooltip: {
        formatter: function() {
          return '<b>'+ this.x +'</b><br/>'+
             Highcharts.numberFormat(this.y, 1)+' Digitized';
        }
      },
      series: [{
          name: 'Total Digitized Assets',
          pointWidth: 10,
          borderWidth: 0,
          
          data: [34, 21, 20, 20, 19, 19, 19, 18, 18,
            17, 16, 15, 14, 14, 13, 12, 12, 11,
            101, 109,1,42,62,92,2,3,5,7,12,
            10,12,14,12,192,72,42,22,52,82,112,13,162,106,18,142,62,72,102,92,82],
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