//[Dashboard Javascript]

//Project:	Lion Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	

	
// Morris-chart
	
	 //Attendance BAR CHART
    var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [
        {y: '18/4/2018', a: 80, b: 95},
        {y: '19/4/2018', a: 85, b: 95},
        {y: '20/4/2018', a: 83, b: 86},
        {y: '21/4/2018', a: 77, b: 92},
        {y: '22/4/2018', a: 65, b: 100},
        {y: '23/4/2018', a: 100, b: 60},
		{y: '24/4/2018', a: 89, b: 90}
      ],
		barColors: ['#4d7cff', '#f2426d'],
		barSizeRatio: 0.5,
		barGap:5,
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Students', 'Employee'],
		hideHover: 'auto'
    });
	
	//-------------------------------------------------------------------------------
	// revenue CHART
    var area = new Morris.Area({
      element: 'revenue-chart',
      resize: true,
      data: [
        { y: '2014', a: 50,  b: 10 },
		{ y: '2015', a: 75,  b: 50 },
		{ y: '2016', a: 30,  b: 80 },
		{ y: '2017', a: 50,  b: 50 },
		{ y: '2018', a: 75,  b: 10 },
		{ y: '2019', a: 50,  b: 40 },
		{ y: '2020', a: 100, b: 70 }
      ],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Commercial Projects', 'Residential Projects'],
		fillOpacity: 0,
		lineWidth:1,
		lineColors: ['#4d7cff', '#51ce8a'],
		hideHover: 'auto'
    });
	
	
	//------------------------------------------------------------------------------
	
	var donut = new Morris.Donut({
      element: 'daily-inquery',
      resize: true,
      colors: ["#51ce8a", "#4d7cff", "#733aeb"],
      data: [
        {label: "On Site", value: 300},
        {label: "By eMail", value: 55},
        {label: "By Phone", value: 100}
      ],
      hideHover: 'auto'
    });
	
	
}); // End of use strict


