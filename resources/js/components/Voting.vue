<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Votes</div>
                    <div class="candidates">
                        <ul>
                            <li v-for="candidate in candidates"><button @click="incrementVotes(candidate.id)">{{ candidate.name }}</button></li>
                        </ul>
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
            drawChart(names, votes) {
              let ctx = document.getElementById("myChart");
              this.chart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels: names,
                      datasets: [{
                          label: '# of Votes',
                          data: votes,
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
            },
            incrementVotes(candidate_id) {
              axios.post('/candidates/' + candidate_id, {})
              .then((response) => {
                let candidates = response.data.data
                this.drawChart(_.map(candidates, 'name'), _.map(candidates, 'votes_count'))
              }).catch((error) => {
                  console.error(error)
              })
            }
        },
        mounted() {
            this.drawChart()
        },
        created() {
          axios.get('/candidates')
            .then((response) => {
              this.candidates = response.data.data;
              this.drawChart(_.map(this.candidates, 'name'), _.map(this.candidates, 'votes_count'))
            }).catch((error) => {
              console.error(error)
            })
        }
    }
</script>
