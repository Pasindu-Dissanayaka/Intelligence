@extends('layouts.default')

@section('title')
Chat Analytics
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 position-relative z-index-2">
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Analytics</h3>
                <p class="mb-4">
                    Check the sales, value and bounce rate by country.
                </p>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 mb-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-0 ">Website Views</h6>
                            <p class="text-sm ">Your visits to this WebApp over the course of the week</p>
                            <div class="pe-2">
                                <div class="chart">
                                    <canvas id="chart-web-views" class="chart-canvas" height="180"></canvas>
                                </div>
                            </div>
                            <hr class="dark horizontal">
                            <div class="d-flex ">
                                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                                <p class="mb-0 text-sm"> updated 2 days ago </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-6">
                    <div class="card ">
                        <div class="card-body">
                            <h6 class="mb-0 "> Daily Chat Analytics </h6>
                            <p class="text-sm ">Your AI Usage over the course of the week</p>
                            <div class="pe-2">
                                <div class="chart">
                                    <canvas id="chart-daily-usage" class="chart-canvas" height="180"></canvas>
                                </div>
                            </div>
                            <hr class="dark horizontal">
                            <div class="d-flex ">
                                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                                <p class="mb-0 text-sm"> updated now </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card  mb-2">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Total Messages</p>
                                    <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">weekend</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+0% </span>than last week</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card  mb-2">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">User Message Count</p>
                                    <h4 class="mb-0">{{ $stats['user_sent'] }}</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">leaderboard</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+0% </span>than last month</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card  mb-2">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Agent Message Count</p>
                                    <h4 class="mb-0">{{ $stats['bot_replies'] }}</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">email</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+0% </span>than last month</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card  mb-2">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Total Billable Token Count</p>
                                    <h4 class="mb-0">{{ $stats['token_estimate'] }}</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">money</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Just updated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ \Leaf\DevTools::console($daily); }}
    @endsection

    @section('custom_js')
    <script src="{{assets('js/plugins/chartjs.min.js')}}"></script>
    <script>
        var ctx2 = document.getElementById("chart-daily-usage").getContext("2d");
        const dailyData = {!! json_encode($daily) !!};

        const labels = Object.keys(dailyData);
        const userData = labels.map(date => dailyData[date].user || 0);
        const botData = labels.map(date => dailyData[date].bot || 0);

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                        label: "User messages",
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: "#1E88E5",
                        pointBorderColor: "transparent",
                        borderColor: "#1E88E5",
                        backgroundColor: "transparent",
                        fill: true,
                        data: userData,
                        maxBarThickness: 6
                    },
                    {
                        label: "Bot replies",
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: "#43A047",
                        pointBorderColor: "transparent",
                        borderColor: "#43A047",
                        backgroundColor: "transparent",
                        fill: true,
                        data: botData,
                        maxBarThickness: 6
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#444'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [4, 4],
                            color: '#e5e5e5'
                        },
                        ticks: {
                            display: true,
                            color: '#737373',
                            padding: 10,
                            font: {
                                size: 12,
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#737373',
                            padding: 10,
                            font: {
                                size: 12,
                                lineHeight: 2
                            },
                        }
                    },
                },
            }
        });
    </script>
    @endsection