/**
 * Copyright (c) 2009 - 2010 Chris Leonello
 * jqPlot is currently available for use in all personal or commercial projects 
 * under both the MIT and GPL version 2.0 licenses. This means that you can 
 * choose the license that best suits your project and use it accordingly. 
 *
 * The author would appreciate an email letting him know of any substantial
 * use of jqPlot.  You can reach the author at: chris dot leonello at gmail 
 * dot com or see http://www.jqplot.com/info.php .  This is, of course, 
 * not required.
 *
 * If you are feeling kind and generous, consider supporting the project by
 * making a donation at: http://www.jqplot.com/donate.php .
 *
 * Thanks for using jqPlot!
 * 
 */
(function($) {    
    /**
     * Class: $.jqplot.CanvasThresholdLinesRenderer
     * Plugin renderer to draw n horizontal threshold lines.
		 * 
     * > seriesDefaults: {
     * >   thresholdLines: {
		 * >          lineColor: '#8f8f8f',
		 * >					labelColor: '#454545',	
		 * >          xValues: [33.0, 78.0, 105.0, 155.0],
		 * >          yValues: [25.0, 50.0, 75.0, 5.0],
		 * >          showLabel: true,     -> [true|false]
		 * >          labelLocation: 'nw'  -> ['nw'|'ne'|'sw'|'se']
		 * >   }
     * > }
     *
     * Author: Valentin Treu
     * Last update: v1.0.2  2010-10-22
     */
    $.jqplot.CanvasThresholdLinesRenderer = function(options){
				this.lineColor  = '#333333';
        this.labelColor = '#666666';
				this.xValues = [];
				this.yValues = [];
				this.showLabel = true;
				this.labelLocation = 'nw'; 
        this.font  = '8pt Arial';
    };

    $.jqplot.postSeriesInitHooks.push(parseOptions);
    $.jqplot.preDrawSeriesHooks.push(drawLines);

    function parseOptions (seriesDefaults, options) {
	
				this.thresholdRenderer = new $.jqplot.CanvasThresholdLinesRenderer();
        $.extend(true, this.thresholdRenderer, seriesDefaults.thresholdLines, this.thresholdLines);
    }
    
    function drawLines(sctx, options) {
	
        options = $.extend(true, {}, this.thresholdRenderer, options);
				var inputDataX = [];
				var inputDataY = [];
				for (var i=0; i < options.xValues.length; i++) {
					inputDataX.push([options.xValues[i], 0]);
				}
        for (var i=0; i < options.yValues.length; i++) {
					inputDataY.push([0, options.yValues[i]]);
				}
				var gridDataX = this.renderer.makeGridData.call(this, inputDataX);
				var gridDataY = this.renderer.makeGridData.call(this, inputDataY);
        sctx.save();
				
				
				for (var i=0; i < inputDataY.length; i++) {
					sctx.beginPath();
					sctx.moveTo(0, gridDataY[i][1]);
					sctx.lineTo(sctx.canvas.width, gridDataY[i][1]);
					sctx.strokeStyle = options.lineColor;
					sctx.stroke();
					if (options.showLabel == true) {
						var labelCoordinates = determineLabelCoordinatesHorizontal(sctx, options.labelLocation, options.yValues[i]);
						sctx.font = options.font;
						sctx.fillStyle = options.labelColor;
						sctx.fillText(options.yValues[i], labelCoordinates[0], gridDataY[i][1] + labelCoordinates[1]);
					}
					
				}
				for (var i=0; i < inputDataX.length; i++) {
					sctx.beginPath();
					sctx.moveTo(gridDataX[i][0], 0);
					sctx.lineTo(gridDataX[i][0], sctx.canvas.height);
					sctx.strokeStyle = options.lineColor;
					sctx.stroke();
					if (options.showLabel == true) {
						var labelCoordinates = determineLabelCoordinatesVertical(sctx, options.labelLocation, options.xValues[i]);
						sctx.font = options.font;
						sctx.fillStyle = options.labelColor;
						sctx.fillText(options.xValues[i], gridDataX[i][0] + labelCoordinates[0], labelCoordinates[1]);
					}
					
				}
        
				sctx.restore();
    }
		
		// this is for label placement of horizontal lines
    function determineLabelCoordinatesHorizontal(context, location, string) {
			var result = [];
			var width = context.measureText(string).width;
			if (location == 'nw' || location == 'sw') {
				result.push(3);
			} else {
				result.push(context.canvas.width - width - 5);
			}
			if (location == 'nw' || location == 'ne') {
				result.push(-5);
			} else {
				result.push(12);
			}
			return result;
	  }
		
		// this is for label placement of vertical lines
		function determineLabelCoordinatesVertical(context, location, string) {
			var result = [];
			var width = context.measureText(string).width;
			if (location == 'nw' || location == 'sw') {
				result.push(-(width+3));
			} else {
				result.push(3);
			}
			if (location == 'nw' || location == 'ne') {
				result.push(12);
			} else {
				result.push(context.canvas.height - 3);
			}
			return result;
	  }
    
})(jQuery);