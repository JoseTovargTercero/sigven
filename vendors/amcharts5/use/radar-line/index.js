// Create root3 element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root3 = am5.Root.new("chartdivRadar");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root3.setThemes([
  am5themes_Animated.new(root3),
  am5themes_Material.new(root3)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
var chart = root3.container.children.push(am5radar.RadarChart.new(root3, {
  panX: false,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX"
}));

// Add cursor
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Cursor
var cursor = chart.set("cursor", am5radar.RadarCursor.new(root3, {
  behavior: "zoomX"
}));

cursor.lineY.set("visible", false);


// Create axes and their renderers
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_axes
var xRenderer = am5radar.AxisRendererCircular.new(root3, {});
xRenderer.labels.template.setAll({
  radius: 10
});

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root3, {
  maxDeviation: 0,
  categoryField: "category",
  renderer: xRenderer,
  tooltip: am5.Tooltip.new(root3, {})
}));

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root3, {
  renderer: am5radar.AxisRendererRadial.new(root3, {})
}));


// Create series
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_series
var series = chart.series.push(am5radar.RadarLineSeries.new(root3, {
  name: "Series",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  categoryXField: "category",
  tooltip: am5.Tooltip.new(root3, {
    labelText: "{valueY}"
  })
}));

series.bullets.push(function () {
  return am5.Bullet.new(root3, {
    sprite: am5.Circle.new(root3, {
      radius: 5,
      fill: series.get("fill")
    })
  });
});


series.appear(1000);
chart.appear(1000, 100);