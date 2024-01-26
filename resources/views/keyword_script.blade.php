<script>
$(document).ready(function() {

    <?php if(session()->get('searchKeyword') || session()->get('createdFrom') || session()->get('createdTo')){ ?>
    $('.filter-section').toggle();
    <?php  } ?>
});

function showSuccessMessage(message) {
    swal.fire('Success', message, 'success');
}

function searchWord() {
    var formData = $("#keywordForm").serialize();
    $.ajax({
        url: 'add/keyword',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.success) {
                // swal.fire('success' , response.message);
                swal.fire({
                    title: 'success',
                    text: response.message,
                    type: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    location.reload();
                });
            }
        },
        error: function(errors) {
            console.log(errors);
            // swal.fire('Error' , errors);
        }
    });
}

function deleteKeyword(id) {

    swal.fire({
        title: 'Are you sure',
        text: 'You want to delete the keyword',
        confirmButtonText: 'Yes',
        showCancelButton: true,
        cancelButtonText: 'No',
    }).then(function(result) {

        if (result.value) {
            $.ajax({
                url: '/delete/keyword/' + id,
                type: 'get',
                dataType: 'json',
                data: {
                    id,
                    id
                },
                success: function(response) {
                    swal.fire({
                        title: 'success',
                        text: response.message,
                        type: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function(response) {
                    console.log(response)
                    swal.fire('Error', 'Something went wrong', 'error')
                }

            });

        }
    });

}

function resetForm() {
    $('#searchFilter').val('');
    $('#submit').click();
}

function toggleFilter() {
    $('.filter-section').toggle();
    if ($('#toggle_filter').text() == 'Show Filters') {
        $('#toggle_filter').text('Hide Filters');
    } else {
        $('#toggle_filter').text('Show Filters');
    }
    console.log(this);
}


if ($('#coin_distribution').length) {

    zingchart.THEME = "classic";

    var myConfig = {
        "globals": {
            "font-family": "Roboto"
        },
        "graphset": [{
                "type": "pie",
                "background-color": "#fff",
                "height": '300px',
                "legend": {
                    "background-color": "none",
                    "border-width": 0,
                    "shadow": false,
                    "layout": "float",
                    "margin": "auto auto 16% auto",
                    "marker": {
                        "border-radius": 3,
                        "border-width": 0
                    },
                    "item": {
                        "color": "%backgroundcolor"
                    }
                },
                "plotarea": {
                    "background-color": "#FFFFFF",
                    "border-color": "#DFE1E3",
                    "margin": "25% 8%"
                },
                "labels": [{
                    "x": "45%",
                    "y": "47%",
                    "width": "10%",
                    "text": "<?php echo $total_email; ?> <br>Emails",
                    "font-size": 17,
                    "font-weight": 700
                }],
                "plot": {
                    "size": 80,
                    "slice": 70,
                    "margin-right": 0,
                    "border-width": 0,
                    "shadow": 0,
                    "value-box": {
                        "visible": true
                    },
                    "tooltip": {
                        "text": "%v",
                        "shadow": false,
                        "border-radius": 2
                    }
                },
                "series": [{
                        "values": [<?php echo $total_email; ?>],
                        "text": "Total Email",
                        "background-color": "#da5c54"
                    },
                    {
                        "values": [<?php echo $email_sent; ?>],
                        "text": "Email Sent",
                        "background-color": "#fd9c21"
                    },
                    {
                        "values": [<?php echo $pending_email; ?>],
                        "text": "Pending Email",
                        "background-color": "#2c13f8"
                    }
                ]
            }

        ]
    };

    zingchart.render({
        id: 'coin_distribution',
        data: myConfig,
    });
}

if ($('#seolinechart1').length) {
    var ctx = document.getElementById("seolinechart1").getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',
        // The data for our dataset
        // data: {
        //     labels: ["January", "February", "March", "April", "May", "June", "July", "January", "February", "March", "April", "May"],
        //     datasets: [{
        //         label: "Likes",
        //         backgroundColor: "rgba(104, 124, 247, 0.6)",
        //         borderColor: '#8596fe',
        //         data: [18, 41, 86, 49, 20, 35, 20, 50, 49, 30, 45, 25],
        //     }]
        // },
        // Configuration options go here
        options: {
            legend: {
                display: false
            },
            animation: {
                easing: "easeInOutBack"
            },
            scales: {
                yAxes: [{
                    display: !1,
                    ticks: {
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold",
                        beginAtZero: !0,
                        maxTicksLimit: 5,
                        padding: 0
                    },
                    gridLines: {
                        drawTicks: !1,
                        display: !1
                    }
                }],
                xAxes: [{
                    display: !1,
                    gridLines: {
                        zeroLineColor: "transparent"
                    },
                    ticks: {
                        padding: 0,
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold"
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0, // disables bezier curves
                }
            }
        }
    });
}

if ($('#seolinechart2').length) {
    var ctx = document.getElementById("seolinechart2").getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',
        // The data for our dataset
        // data: {
        //     labels: ["January", "February", "March", "April", "May", "June", "July", "January", "February", "March", "April", "May"],
        //     datasets: [{
        //         label: "Share",
        //         backgroundColor: "rgba(96, 241, 205, 0.2)",
        //         borderColor: '#3de5bb',
        //         data: [18, 41, 86, 49, 20, 35, 20, 50, 49, 30, 45, 25],
        //     }]
        // },
        // Configuration options go here
        options: {
            legend: {
                display: false
            },
            animation: {
                easing: "easeInOutBack"
            },
            scales: {
                yAxes: [{
                    display: !1,
                    ticks: {
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold",
                        beginAtZero: !0,
                        maxTicksLimit: 5,
                        padding: 0
                    },
                    gridLines: {
                        drawTicks: !1,
                        display: !1
                    }
                }],
                xAxes: [{
                    display: !1,
                    gridLines: {
                        zeroLineColor: "transparent"
                    },
                    ticks: {
                        padding: 0,
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold"
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0, // disables bezier curves
                }
            }
        }
    });
}

if ($('#seolinechart6').length) {
    var ctx = document.getElementById("seolinechart6").getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',
        // The data for our dataset
        // data: {
        //     labels: ["January", "February", "March", "April", "May", "June", "July", "January", "February", "March", "April", "May"],
        //     datasets: [{
        //         label: "Share",
        //         backgroundColor: "rgba(96, 241, 205, 0.2)",
        //         borderColor: '#3de5bb',
        //         data: [18, 41, 86, 49, 20, 35, 20, 50, 49, 30, 45, 25],
        //     }]
        // },
        // Configuration options go here
        options: {
            legend: {
                display: false
            },
            animation: {
                easing: "easeInOutBack"
            },
            scales: {
                yAxes: [{
                    display: !1,
                    ticks: {
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold",
                        beginAtZero: !0,
                        maxTicksLimit: 5,
                        padding: 0
                    },
                    gridLines: {
                        drawTicks: !1,
                        display: !1
                    }
                }],
                xAxes: [{
                    display: !1,
                    gridLines: {
                        zeroLineColor: "transparent"
                    },
                    ticks: {
                        padding: 0,
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold"
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0, // disables bezier curves
                }
            }
        }
    });
}
</script>