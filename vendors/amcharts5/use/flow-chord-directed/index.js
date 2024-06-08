// Create root5 element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root5 = am5.Root.new("chartdivEdad");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root5.setThemes([
  am5themes_Animated.new(root5),
  am5themes_Material.new(root5)
]);


// Create series
// https://www.amcharts.com/docs/v5/charts/flow-charts/
var series5 = root5.container.children.push(am5flow.ChordDirected.new(root5, {
  startAngle: 90,
  padAngle: 1,
  linkHeadRadius: null,
  sourceIdField: "from",
  targetIdField: "to",
  valueField: "value"

}));

series5.nodes.labels.template.setAll({
  textType: "radial",
  centerX: 0,
  fontSize: 9
});

series5.links.template.set("fillStyle", "source");




// Make stuff animate on load
series5.appear(1000, 100);