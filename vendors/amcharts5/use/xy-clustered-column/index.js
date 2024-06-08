var root2 = am5.Root.new("chartdivJefes");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root2.setThemes([
  am5themes_Animated.new(root2),
  am5themes_Material.new(root2)
  
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root2.container.children.push(am5xy.XYChart.new(root2, {
  panX: false,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX",
  layout: root2.verticalLayout
}));


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(
  am5.Legend.new(root2, {
    centerX: am5.p50,
    x: am5.p50
  })
);

