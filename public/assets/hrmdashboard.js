
var options = {
    series: [{
        name: 'Designing',
        type: 'column',
        data: [53, 37, 28, 47, 53, 22, 57, 41, 44, 22, 45, 55]
    }, {
        name: 'Development',
        type: 'column',
        data: [37, 22, 20, 33, 30, 17, 45, 28, 35, 18, 37, 48]
    }, {
        name: 'SEO',
        type: 'line',
        data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43, 27]
    },
    ],
    chart: {
        toolbar: {
            show: false
        },
        height: 300,
        type: 'line',
        stacked: false,
        fontFamily: 'Poppins, sans-serif',
    },
    grid: {
        borderColor: '#f5f4f4',
        strokeDashArray: 3
    },
    dataLabels: {
        enabled: false
    },
    title: {
        text: undefined,
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    yaxis: [
        {
            show: true,
            axisTicks: {
                show: true,
            },
            axisBorder: {
                show: false,
                color: '#4eb6d0'
            },
        }
    ],
    tooltip: {
        enabled: true,
    },
    legend: {
        show: true,
        position: "top",
        offsetX: 0,
        offsetY: 8,
        markers: {
            width: 10,
            height: 10,
            strokeWidth: 0,
            strokeColor: '#fff',
            fillColors: undefined,
            radius: 2,
            customHTML: undefined,
            onClick: undefined,
            offsetX: 0,
            offsetY: 0
        },
    },
    stroke: {
			show: true,
			width: [1, 1, 2],
            curve: 'smooth',
    },
    plotOptions: {
        bar: {
            columnWidth: "30%",
            borderRadius: 3
        }
    },
    colors: ["rgb(1, 98, 232)", "rgb(238, 51, 94)", "rgb(34 ,192 ,60)"]
};
// var chart1 = new ApexCharts(document.querySelector("#performanceReport"), options);
// chart1.render();
//performance by category//

