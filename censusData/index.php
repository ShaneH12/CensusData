<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>

.bar {
  fill: steelblue;
}

.bar:hover {
  fill: brown;
}

.axis--x path {
  display: none;
}

</style>
<?php
function loadCsv($path) {
    $csvFile = file($path);
    $csv = array_map('str_getcsv', $csvFile);
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv); # remove column header
    return $csv;
}
// function calculateAverages($data) {
//   $averagedData = [];
//   forEach($data as $dataPoint){
//     $date = DateTime::createFromFormat("Y-m-d", $dataPoint['DATE']);
//
//     $year = $date->format("Y");
//     $month = $date->format("m");
//
//     // if(!$averagedData[$year]){
//     //   $averagedData[$year] = [];
//   }
//   }
//     if(!$averagedData[$year][$month]){
//        $averagedData[intval($year)][intval($month)] = [
//          'tempTotal'=>intval($dataPoint['TMAX']),
//          'prcpTotal'=> intval($dataPoint['PRCP']),
//          'tempAvg'=>intval($dataPoint['TMAX']),
//          'prcpAvg'=> intval($dataPoint['PRCP']),
//          'numDays' => 1];
//     } else {
//        $existingMonthData = $averagedData[$year][$month];
//        $averagedData[$year][$month]['numDays']++;
//
//        $averagedData[$year][$month]['tempTotal'] = $averagedData[$year][$month]['tempTotal'] + intval($dataPoint['tmax']);
//        $averagedData[$year][$month]['prcpTotal'] = $averagedData[$year][$month]['prcpTotal'] + intval($dataPoint['PRCP']);
//        $averagedData[$year][$month]['tempAvg'] = $averagedData[$year][$month]['tempTotal'] / $averagedData[$year][$month]['numDays'];
//        $averagedData[$year][$month]['prcpAvg'] = $averagedData[$year][$month]['prcpTotal'] / $averagedData[$year][$month]['numDays'];
//
//     }
//   }
//   return $averagedData;
// }
$censusData = loadCsv('http://localhost/censusData/louisiana_cen2.csv');
// $censusData = loadCsv('http://localhost/dataAnalysis/miami-temperature.csv');
// $precipData = loadCsv('http://localhost/dataAnalysis/louisiana_cen.csv');
// $mergedData = [];
// $i = 0;
// foreach($censusData as $temp) {
//   $mergedData[] = array_merge($temp, $precipData[$i]);
//   $i++;
// }

// $averageData = calculateAverages($mergedData);
// $censusData = [];
// // $censusData = "[";
// foreach($averageData as $data){
//   foreach($data as $month){
//     $censusData[] = $month['tempAvg'];
    // $tempAvg = $month['tempAvg'];
    // if($censusData == "[") {
    //   $censusData = $censusData . "$tempAvg";
    // } else {
    //   $censusData = $censusData . ", $tempAvg";
    // }
//   }
// }
// echo("<input id='temp' type='hidden' value='".implode(", ", $censusData)."' />");
//  var_dump($censusData);
// // $censusData = $censusData . "]";
// //  $censusData = '[80, 100, 56, 120, 180, 30, 40, 120, 160]';
// // $jsoncensusData = json_encode($censusData);
// $population = array();
// foreach($censusData as $data){
// array_push($population, $data["Population"]);

?>
<h1>Louisiana Census 18-24 Year Old Population</h1>
<html>
  <head>
    <!-- <link rel="stylesheet" href="index.css"> -->
  </head>
  <body>
    <svg width="3650" height="300"></svg>
    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script>

      var svg = d3.select("svg"),
          margin = {top: 20, right: 20, bottom: 30, left: 70},
          width = +svg.attr("width") - margin.left - margin.right,
          height = +svg.attr("height") - margin.top - margin.bottom;

      var x = d3.scaleBand().rangeRound([0, width]).padding(0.1),
          y = d3.scaleLinear().rangeRound([height, 0]);

      var g = svg.append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      d3.csv("louisiana_cen2.csv", function(d) {
        d.Population = +d.Population;
        return d;
      }, function(error, data) {
        if (error) throw error;

        x.domain(data.map(function(d) { return d.County; }));
        y.domain([0, d3.max(data, function(d) { return d.Population; })]);

        g.append("g")
            .attr("class", "axis axis--x")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x));

        g.append("g")
            .attr("class", "axis axis--y")
            .call(d3.axisLeft(y).ticks(10, "%"))
          .append("text")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", "0.71em")
            .attr("text-anchor", "end")
            .text("population");

        g.selectAll(".bar")
          .data(data)
          .enter().append("rect")
            .attr("class", "bar")
            .attr("x", function(d) { return x(d.County); })
            .attr("y", function(d) { return y(d.Population); })
            .attr("width", x.bandwidth())
            .attr("height", function(d) { return height - y(d.Population); });
      });

    // var svgWidth = 5000;
    // var svgHeight = 300;
    // var svg = d3.select('svg')
    //     .attr("width", svgWidth)
    //     .attr("height", svgHeight)
    //     .attr("class", "bar-chart");
    //
        // var dataset = <?php echo("[" . implode(", ", $population) . "]"); ?>;
    //     // var dataset = document.getElementById('temp').value;
    //     console.log(dataset);
    //     var barPadding = 5;
    //     var barWidth = (svgWidth / dataset.length);
    //     var barChart = svg.selectAll("rect")
    //         .data(dataset)
    //         .enter()
    //         .append("rect")
    //         .attr("y", function(d) {
    //             return svgHeight - d
    //         })
    //         .attr("height", function(d) {
    //             return d;
    //         })
    //         .attr("width", barWidth - barPadding)
    //         .attr("transform", function (d, i) {
    //              var translate = [barWidth * i, 0];
    //              return "translate("+ translate +")";
    //         });
      </script>
  </body>
</html>

<!-- <table class="table">
  <tr>
    <th>Year</th>
    <th>Month</th>
    <th>Temp</th>
    <th>Precip</th>
  </tr>
<?php
// foreach($averageData as $year => $data) {
//   for($i = 01; $i <= 12; $i++){
//     $temp = $data[$i]['tempAvg'];
//     $precip = $data[$i]['prcpAvg'];
//     echo("<tr>");
//     echo("<td>$year</td>");
//     echo("<td>$i</td>");
//     echo("<td>$temp</td>");
//     echo("<td>$precip</td>");
//     echo("</tr>");
//   }
// }
?>
</table> -->
