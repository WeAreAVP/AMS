

<div style="width: 50%;float: right;">
  <div class="my-navbar">
    <div>
      Scheduled vs. Completed
    </div>

  </div>
<ul class="nav nav-tabs">
  <li class="active"><a href="#tv_radio" data-toggle="tab">Radio & TV</a></li>
  <li><a href="#all_formats" data-toggle="tab">All Formats</a></li>

</ul>
<div class="tab-content">
  <div class="tab-pane active" id="tv_radio" style="min-width: 400px;height: 380px; margin: 0 auto;"></div>
  <div class="tab-pane" id="all_formats" style="width: 605px;height: 380px; margin: 0 auto;"></div>
</div>
 
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
  $(function () {
    
    var chart;
    var chart1;
    $(document).ready(function() {
      Highcharts.theme = {
        colors: [
          '#000000', 
          '#7D7D7D', 
        ],
        chart: {
          plotBackgroundColor: 'whiteSmoke',
          plotShadow:false,
          shadow:false,
          pointBorderWidth:0
         
         
        },
   
        xAxis: {
          gridLineWidth: 0,
          
          labels: {
            style: {
              color: '#060b10',
//              fontWeight: 'bold',
              fontSize: '15px'
            }
          }
     
        },
        yAxis: {
          lineWidth: 0,
          tickWidth: 0,
          
          labels: {
            style: {
              color: '#000000'
              //              fontWeight: 'bold'
            }
          }
     
        },
        legend: {
          itemStyle: {
            font: 'Verdana, sans-serif',
            color: '#000000'
          },
          itemHoverStyle: {
            color: '#000000'
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

      // Apply the theme
      var highchartsOptions = Highcharts.setOptions(Highcharts.theme);

      chart = new Highcharts.Chart({
        chart: {
          renderTo: 'tv_radio',
          type: 'column'
        },
        title: {
          text: ''
        },
        subtitle: {
          text: ''
        },
        plotOptions:{
          column:{
            borderWidth:0
          }
        },
        credits: {
          enabled: false,
          href: "",
          text: "AMS"
        },legend: {
          enabled: false
        },
        
        xAxis: {
          lineWidth: 0,
          tickWidth:0,
          categories: [
            'Radio',
            'TV'
                    
          ]
        },
        yAxis: {
          min: 0,
          max:1000,
          tickInterval:100,
          gridLineWidth: 0,
          title: {
            text: ''
          }
        },
        
        tooltip: {
          formatter: function() {
            return '<b>'+ this.x +'</b><br/>'+
              Highcharts.numberFormat(this.y, 0);
          }
        },
        
        series: [{
            name: 'Assets Scheduled',
            shadow:false,
            data: [499, 715],
            pointWidth: 82,
            
            pointBorderColor:'#000000',
            dataLabels: {
              enabled: true,
              rotation: 0,
              color: '#FFFFFF',
              align: 'center',
              x: 0,
              y: 50,
              
              formatter: function() {
                return '<b>'+this.y +'</b><br/>Assets<br/>Scheduled';
              }
            },states: {
              hover: {
                enabled: false
              }
            }
           
    
          }, {
            name: 'Assets Completed',
            shadow:false,
            data: [283, 278],
            pointWidth: 82,
            pointBorderColor:'#000000',
            dataLabels: {
              enabled: true,
              rotation: 0,
              color: '#FFFFFF',
              align: 'center',
              
              x: 0,
              y: 50,
//              style: {
//                //                fontSize: '18px',
//                fontFamily: 'Verdana, sans-serif',
//                fontWeight:'bold',
//                
//              },
              
              formatter: function() {
                return '<b>'+this.y +'</b><br/>Assets<br/>Completed';
              }
              
            },states: {
              hover: {
                enabled: false
              }
            }
            
            
    
          }]
      });
      
      
      chart1 = new Highcharts.Chart({
        chart: {
          renderTo: 'all_formats',
          type: 'column'
        },
        title: {
          text: ''
        },
        subtitle: {
          text: ''
        },
        plotOptions:{
          column:{
            borderWidth:0
            
            
          }
        },
        credits: {
          enabled: false,
          href: "",
          text: "AMS"
        },legend: {
          enabled: false
        },
        
        xAxis: {
          lineWidth: 0,
          tickWidth:0,
          categories: [
            'All Formats'
            
                    
          ]
        },
        yAxis: {
          min: 0,
          max:1000,
          tickInterval:100,
          gridLineWidth: 0,
          title: {
            text: ''
          }
        },
        
        tooltip: {
          formatter: function() {
            return '<b>'+ this.x +'</b><br/>'+
              Highcharts.numberFormat(this.y, 0);
          }
        },
        
        series: [{
            name: 'All Formats Scheduled',
            shadow:false,
            data: [499],
            pointWidth: 90,
            
            
            pointBorderColor:'#000000',
            dataLabels: {
              enabled: true,
              rotation: 0,
              color: '#FFFFFF',
              align: 'center',
              x: 0,
              y: 50,
              
              formatter: function() {
                return '<b>'+this.y +'</b><br/>Assets<br/>Scheduled';
              }
            },states: {
              hover: {
                enabled: false
              }
            }
           
    
          }, {
            name: 'All Formats Completed',
            shadow:false,
            data: [283],
            pointWidth: 90,
            pointBorderColor:'#000000',
            dataLabels: {
              enabled: true,
              rotation: 0,
              color: '#FFFFFF',
              align: 'center',
              
              x: 0,
              y: 50,
//              style: {
//                //                fontSize: '18px',
//                fontFamily: 'Verdana, sans-serif',
//                fontWeight:'bold',
//                
//              },
              
              formatter: function() {
                return '<b>'+this.y +'</b><br/>Assets<br/>Completed';
              }
              
            },states: {
              hover: {
                enabled: false
              }
            }
            
            
    
          }]
      });
    });
    
  });
</script>

