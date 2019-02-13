<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Votes</div>
                    <div class="candidates">
                      <!-- Buttons to cast votes will go in here -->
                    </div>

                    <!-- Our focus right now -->
                    <div class="card-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Chart from 'chart.js'
    import _ from "lodash";

    export default {
        data() {
            return {
                candidates: [],
                chart: null,
            }
        },
        methods: {
            drawChart() {
              let ctx = document.getElementById("myChart");
              this.chart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels: ['candidate 1', 'Candidate 2'],
                      datasets: [{
                          label: '# of Votes',
                          data: [5, 8],
                          borderWidth: 1
                      }]
                  },
                  options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                  }
              });
            }
        },
        created() {
            this.drawChart()
        }
    }
</script>
