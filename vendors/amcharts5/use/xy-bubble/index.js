// Create root6 element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root6 = am5.Root.new("chartdivDist");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root6.setThemes([
  am5themes_Animated.new(root6)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root6.container.children.push(am5xy.XYChart.new(root6, {
  panX: true,
  panY: true,
  wheelY: "zoomXY"
}));


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root6, {
  maxDeviation:1,
  renderer: am5xy.AxisRendererX.new(root6, {
    pan:"zoom"
  }),
  tooltip: am5.Tooltip.new(root6, {})
}));

xAxis.children.moveValue(am5.Label.new(root6, {
  text: "",
  x: am5.p50,
  centerX: am5.p50
}), xAxis.children.length - 1);

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root6, {
  maxDeviation:1,
  renderer: am5xy.AxisRendererY.new(root6, {
    pan:"zoom"
  }),
  tooltip: am5.Tooltip.new(root6, {})
}));

yAxis.children.moveValue(am5.Label.new(root6, {
  rotation: -90,
  text: "Centros de Votacion",
  y: am5.p50,
  centerX: am5.p50
}), 0);


// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.LineSeries.new(root6, {
  calculateAggregates: true,
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "y",
  valueXField: "x",
  valueField: "value",
  tooltip: am5.Tooltip.new(root6, {
    pointerOrientation: "vertical",
    labelText: "[bold]{title}[/]\nVotos Blandos: {value.formatNumber('##,###.')}"
  })
}));

series.strokes.template.set("visible", false);

// Add bullet
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/#Bullets
var circleTemplate = am5.Template.new({});
circleTemplate.adapters.add("fill", function (fill, target) {
  var dataItem = target.dataItem;
  if (dataItem) {
    return am5.Color.fromString(dataItem.dataContext.color);
  }
  return fill
});
series.bullets.push(function () {
  var bulletCircle = am5.Circle.new(root6, {
    radius: 5,
    fill: series.get("fill"),
    fillOpacity: 0.8
  }, circleTemplate);
  return am5.Bullet.new(root6, {
    sprite: bulletCircle
  });
});


// Add heat rule
// https://www.amcharts.com/docs/v5/concepts/settings/heat-rules/
series.set("heatRules", [{
  target: circleTemplate,
  min: 1,
  max: 60,
  dataField: "value",
  key: "radius"
}]);


var background = series.get("tooltip").get("background");
background.set("stroke", root6.interfaceColors.get("alternativeBackground"));
background.adapters.add("fill", function (fill, target) {
  var dataItem = target.dataItem;
  if (dataItem && dataItem.dataContext) {
    return am5.Color.fromString(dataItem.dataContext.color);
  }
  return fill
});


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
chart.set("cursor", am5xy.XYCursor.new(root6, {
  yAxis: yAxis,
  snapToSeries: [series]
}));




// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/#Forcing_appearance_animation
series.appear(1000);
chart.appear(1000, 100);