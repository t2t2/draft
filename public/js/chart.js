CHARTJS = {
	init: function () {
		if($("#earnings-chart").length) {
			CHARTJS.drawChart($("#earnings-chart"))
		}
	},

	chartData: null,
	drawChart: function ($target) {
		if(!CHARTJS.chartData) {
			$target.html('<i class="icon-spinner icon-spin"></i> Loading chart...')
			$.getJSON($target.data("source"), function (data) {
				CHARTJS.chartData = data
				CHARTJS.drawChart($target)
			});
			return
		}
		$target.html("")
		$target.plot(CHARTJS.chartData, {
			legend: {
				placement: 'outsideGrid',
				container: $("#earnings-legend"),
			},
			xaxis: {
				mode: "time",
				//timeformat: "%m/%d"
			},
			yaxis: {
				tickFormatter: function (val, axis) {
					return "$"+val.toMoney(axis.tickDecimals)
				}
			},
			series: {
				lines: {
					show: true
				},
				points: {
					show: true
				}
			}
		});
	}
};
/* http://stackoverflow.com/a/2866613/211088
decimal_sep: character used as deciaml separtor, it defaults to '.' when omitted
thousands_sep: char used as thousands separator, it defaults to ',' when omitted
*/
Number.prototype.toMoney = function(decimals, decimal_sep, thousands_sep)
{
   var n = this,
   c = isNaN(decimals) ? 2 : Math.abs(decimals), //if decimal is zero we must take it, it means user does not want to show any decimal
   d = decimal_sep || '.', //if no decimal separator is passed we use the dot as default decimal separator (we MUST use a decimal separator)

   /*
   according to [http://stackoverflow.com/questions/411352/how-best-to-determine-if-an-argument-is-not-sent-to-the-javascript-function]
   the fastest way to check for not defined parameter is to use typeof value === 'undefined'
   rather than doing value === undefined.
   */
   t = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, //if you don't want to use a thousands separator you can pass empty string as thousands_sep value

   sign = (n < 0) ? '-' : '',

   //extracting the absolute value of the integer part of the number and converting to string
   i = parseInt(n = Math.abs(n).toFixed(c)) + '',

   j = ((j = i.length) > 3) ? j % 3 : 0;
   return sign + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
};
$(document).ready(CHARTJS.init);