jQuery(document).ready(function ($) {
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: pes_ajax.labels,
            datasets: [{
                label: "web",
                backgroundColor: "rgba(192,17,29,0.4)",
                borderColor: "rgba(192,17,29,1)",
                data: pes_ajax.data.web,
                fill: true,
            }, {
                label: "hr",
                backgroundColor: "rgba(0,156,221,0.4)",
                borderColor: "rgba(0,156,221,1)",
                data: pes_ajax.data.hr,
                fill: true,
            }, {
                label: "total",
                backgroundColor: "rgba(104,192,181,0.4)",
                borderColor: "rgba(104,192,181,1)",
                data: pes_ajax.data.total,
                fill: true,
            }]
        },

        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Day'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Downloads'
                    },
                }]
            }
        }
    });
});