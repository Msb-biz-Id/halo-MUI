
// pie chart
var ispiechart = document.getElementById('pie');

var pieChart = new Chart(ispiechart, {
    type: 'pie',
    data: {
        datasets: [{
            data: [21, 16, 48, 31],
            backgroundColor: [
                '#0db4d6',
                '#eeb148',
                '#525ce5',
                '#343a40'
            ],
            borderColor: [
                '#0db4d6',
                '#eeb148',
                '#525ce5',
                '#343a40'
            ],
        }],


        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: [
            'Drixo',
            'Upcube',
            'Vakavia',
            'Devazo'
        ]
    },
});

// bar chart
var isbarchart = document.getElementById('barChart');

var lineChart = new Chart(isbarchart, {
    type: 'bar',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
        datasets: [{
            label: 'Apple',
            data: [46, 57, 59, 54, 62, 58, 64, 60, 66],
            backgroundColor: '#eeb148'
        },
        {
            label: 'Samsung',
            data: [74, 83, 102, 97, 86, 106, 93, 114, 94],
            backgroundColor: '#525ce5'
        },
        {
            label: 'Oppo',
            data: [37, 42, 38, 26, 47, 50, 54, 55, 43],
            backgroundColor: '#23c58f'
        }
    ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        categoryPercentage: 0.4,
        scales: {
            y: {
                display: false,
                gridLines: {
                    drawBorder: false,
                },
                ticks: {
                    fontColor: "#686868"
                }
            },
            x: {
                barPercentage: 0.7,
                categoryPercentage: 0.5,
                ticks: {
                    fontColor: "#7b919e"
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                }
            }
        },
        elements: {
            point: {
                radius: 0
            }
        }
    }
});

// line chart
var islinechart = document.getElementById('lineChart');

var lineChart = new Chart(islinechart, {
    type: 'line',
    data: {
        labels: ["2013", "2014", "2014", "2015", "2016", "2017", "2018", "2019"],
        datasets: [
            {
                label: 'Apple',
                data: [120, 180, 140, 210, 160, 240, 180, 210],
                borderColor: [
                    '#525ce5'
                ],
                borderWidth: 3,
                fill: false,
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#525ce5"
            },
            {
                label: 'Samsung',
                data: [80, 140, 100, 170, 120, 200, 140, 170],
                borderColor: [
                    '#7c8a96'
                ],
                borderWidth: 3,
                fill: false,
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#7c8a96"
            }
        ]
    },
    options: {
        scales: {
            y: {
                gridLines: {
                    drawBorder: false,
                    borderDash: [3, 3],
                    zeroLineColor: '#7b919e'
                },
                ticks: {
                    min: 0,
                    color: "#7b919e"
                },
            },
            x: {
                gridLines: {
                    display: false,
                    drawBorder: false,
                    color: "#ffffff"
                }
            }
        },
        elements: {
            line: {
                tension: 0.2
            },
            point: {
                radius: 4
            }
        },
        stepsize: 1
    }
});

// donut
var isdoughnutchart = document.getElementById('doughnut');

var lineChart = new Chart(isdoughnutchart, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [21, 16, 48, 31],
            backgroundColor: ['#3d8ef8', '#7c8a96', '#11c46e', '#f1b44c'],
            borderColor: ['#3d8ef8', '#7c8a96', '#11c46e', '#f1b44c']
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: [
            'Drixo',
            'Upcube',
            'Vakavia',
            'Devazo'
        ]
    },
});


// radar chart
var isradarchart = document.getElementById('radar');
var lineChart = new Chart(isradarchart, {
    type: 'radar',
    data: {
        labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
        datasets: [
            {
                label: "Unhealthy",
                backgroundColor: 'rgba(223, 227, 233, 0.2)',
                borderColor: "#dfe3e9",
                borderWidth: 1,
                pointBackgroundColor: "#dfe3e9",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#dfe3e9",
                data: [65, 59, 90, 81, 56, 55, 40]
            },
            {
                label: "Healthy",
                backgroundColor: "rgba(61, 142, 248,0.2)",
                borderColor: "#3d8ef8",
                borderWidth: 1,
                pointBackgroundColor: "#3d8ef8",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#3d8ef8",
                data: [28, 48, 40, 19, 96, 27, 100]
            }
        ],
    },
});