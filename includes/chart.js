Chart.register(ChartDataLabels);
Chart.defaults.set('plugins.datalabels', {
    color: '#000',
    align: function(context) {
        var filter_id = context.chart.canvas.id;
        if(filter_id === 'graphCanvas3' || filter_id === 'graphCanvas4') {
            return "right";
        }
        return "center";
        
    },
    font: {
        size: 12
    },
    formatter: function(value, context) {
        var filter_id = context.chart.canvas.id;
        if(filter_id === 'graphCanvas4') {
            return (value / 100).toFixed(4);
        }
        if(filter_id !== 'graphCanvas1') {
            return value + '%';
        }
        return value;
        
    }
});

function getArraySum(a){
    var total=0;
    for(var i in a) { 
        total += a[i];
    }
    return total;
}
    //chart data
    function showGraph(data) {

            var sprint_name = [];
            var v2_delivered = [];
            var lt_delivered = []; 
            var qa_passed = []; 
            var rework = []; 
            var v2_carryover = [];
            var lt_carryover = [];
            var v2_carryover_percentage = [];
            var lt_carryover_percentage = [];
            var lt_reoponed_sp = [];
            var planned_vs_completed_ratio = [];
            var reworkTrend = [];
            var carryoverTrend = [];
            var plannedVsCompleted = [];
            var v2_reopen_percentage = [];
            var lt_reopen_percentage = [];


            for (var i in data) {

                sprint_name.push(data[i].sprint_name);
                v2_delivered.push(+data[i].v2_delivered);
                lt_delivered.push(+data[i].lt_delivered);
                qa_passed.push(+data[i].qa_passed);
                rework.push(+data[i].rework);
                v2_carryover_percentage.push(+data[i].v2_carryover_percentage);
                lt_carryover_percentage.push(+data[i].lt_carryover_percentage);
                v2_carryover.push(+data[i].v2_carryover);
                lt_carryover.push(+data[i].lt_carryover);
                lt_reoponed_sp.push(+data[i].lt_reoponed_sp);
                v2_reopen_percentage.push(+data[i].v2_reopen_percentage);
                lt_reopen_percentage.push(+data[i].lt_reopen_percentage);
                planned_vs_completed_ratio.push(+data[i].planned_vs_completed_ratio);
            }

            var throughputChartdata = {
                labels: sprint_name,
                datasets: [{
                        label: 'V2',
                        data: v2_delivered,
                        backgroundColor: '#3f51b5',
                        stack: 'Stack 0'
                    },
                    {
                        label: 'LT',
                        data: lt_delivered,
                        backgroundColor: '#fd7e14',
                        stack: 'Stack 0'
                    }
                ]
            };

            console.log(throughputChartdata);
           

            var graphTarget = $("#graphCanvas1");

            var barGraph = new Chart(graphTarget, {
                type: 'bar',
                data: throughputChartdata,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Throughput Distribution'
                        },
                    },
                    responsive: true
                }
            });

            var sumQA = getArraySum(qa_passed);
            var sumRW = getArraySum(rework);

            var qualityChartData = {
                labels: ['QA Passed', 'Reopen'],
                datasets: [
                    {
                    label: 'Dataset 1',
                    data: [sumQA, sumRW],
                    backgroundColor: ['#3f51b5', '#fd7e14'],
                    }
                ]
            };
            //console.log(qualityChartData);

            var barGraph2 = new Chart($("#graphCanvas2"), {
                type: 'pie',
                data: qualityChartData,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Quality(V2)'
                        },
                    },
                    responsive: true,
                    
                }
            });

            //
            
            var reworkTrendData = {
                labels: sprint_name,
                datasets: [{
                        label: 'V2',
                        data: v2_reopen_percentage,
                        borderColor: '#3f51b5',
                        backgroundColor: '#3f51b5',
                    },
                    {
                        label: 'LT',
                        data: lt_reopen_percentage,
                        borderColor: '#fd7e14',
                        backgroundColor: '#fd7e14',
                    }
                ]
            };
            //console.log(reworkTrendData);
            var barGraph3 = new Chart($("#graphCanvas3"), {
                type: 'line',
                data: reworkTrendData,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Reopen Trend'
                        },
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value, index, values) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    responsive: true,
                    
                }
            });
            //
            var carryoverTrendData = {
                labels: sprint_name,
                datasets: [{
                        label: 'V2',
                        data: v2_carryover_percentage,
                        borderColor: '#3f51b5',
                        backgroundColor: '#3f51b5',
                    },
                    {
                        label: 'LT',
                        data: lt_carryover_percentage,
                        borderColor: '#fd7e14',
                        backgroundColor: '#fd7e14',
                    }
                ]
            };
            
            //console.log(carryoverTrendData);
            var barGraph4 = new Chart($("#graphCanvas4"), {
                type: 'line',
                data: carryoverTrendData,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Carryover Trend'
                        },
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value, index, values) {
                                    return (value / 100).toFixed(1);
                                }
                            }
                        }
                    },
                    responsive: true,
                    
                }
            });
            //
            var pvcData = {
                labels: sprint_name,
                datasets: [{
                        label: 'Ratio',
                        data: planned_vs_completed_ratio,
                        backgroundColor: '#3f51b5',
                    }
                ]
            };
            console.log(pvcData);
            var barGraph5 = new Chart($("#graphCanvas5"), {
                type: 'bar',
                data: pvcData,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Planned Vs Completed Ratio'
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value, index, values) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    responsive: true,
                }
            });
            console.log(data);
    }
