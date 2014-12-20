(function($) {

	"use strict";

	$(document).ready(function() {

		d3.json(Craft.getCpUrl('postmaster/data/success-fail-rate'), function(data) {
			data = [{
				'label': Craft.t('Success'),
				'value': data.sent
			},{
				'label': Craft.t('Failed'),
				'value': data.failed
			}]

			nv.addGraph(function() {
				var chart = nv.models.pieChart()
				  	.x(function(d) { return d.label })
				  	.y(function(d) { return d.value })
				  	.margin({left: 0, top: 30, right: 0, bottom: 20})
				  	.showLabels(true)
				  	.showLegend(false);

				d3.select(".success-failure-chart svg")
				    .datum(data)
				    .transition().duration(350)
				    .call(chart);

				return chart;
			});
		});

		/*
		d3.json(Craft.getCpUrl('postmaster/data/monthly-breakdown'), function(data) {
			var json = [{
				key: Craft.t('Parcels'),
				values: data.parcels
			},{
				key: Craft.t('Notifications'),
				values: data.notifications
			}];

			nv.addGraph(function() {
				var chart = nv.models.stackedAreaChart()
				              .margin({left: 0, top: 30, right: 0, bottom: 20})
				              .x(function(d) { return d[0] })   //We can modify the data accessor functions...
				              .y(function(d) { return d[1] })   //...in case your data is formatted differently.
				              .transitionDuration(500)
				              .showControls(false)
				              .clipEdge(true);

				//Format x-axis labels with custom function.
				chart.xAxis
				    .tickFormat(function(d) { 
				      return d3.time.format('%x')(new Date(d)) 
				});

				chart.yAxis
				    .tickFormat(d3.format(',.2f'));

				d3.select('.monthly-breakdown-chart svg')
				  .datum([{
					key: Craft.t('Parcels'),
					values: data.parcels
				  },{
					key: Craft.t('Notifications'),
					values: data.notifications
				  }])
				  .call(chart);

				nv.utils.windowResize(chart.update);

				return chart;
			});
		});
		*/

	});

}(jQuery));