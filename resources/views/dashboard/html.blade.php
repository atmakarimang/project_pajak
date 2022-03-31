<!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-12 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-th mr-1"></i>
                    Sales
                  </h3>
                  <div class="card-tools">
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content p-0">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                      {{-- <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas> --}}
                      <canvas id="myChart" width="300" style="height: 100%;"></canvas>
                    </div>
                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                      <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                    </div>
                  </div>
                </div><!-- /.card-body -->
              </div>
              <!-- /.card -->
            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            {{-- <section class="col-lg-5 connectedSortable">

              <!-- Map card -->
              <div class="card bg-gradient-primary">
                <!-- /.card-body-->
                <div class="card-footer bg-transparent">
                  <div class="row">
                    <div class="col-4 text-center">
                      <div id="sparkline-1"></div>
                      <div class="text-white">Visitors</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-4 text-center">
                      <div id="sparkline-2"></div>
                      <div class="text-white">Online</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-4 text-center">
                      <div id="sparkline-3"></div>
                      <div class="text-white">Sales</div>
                    </div>
                    <!-- ./col -->
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.card -->

              <!-- solid sales graph -->
              <div class="card bg-gradient-info">
                <div class="card-header border-0">
                  <h3 class="card-title">
                    <i class="fas fa-th mr-1"></i>
                    Sales Graph
                  </h3>

                  <div class="card-tools">
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <canvas class="chart" id="line-chart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-transparent">
                  <div class="row">
                    <div class="col-4 text-center">
                      <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                        data-fgColor="#39CCCC">

                      <div class="text-white">Mail-Orders</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-4 text-center">
                      <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                        data-fgColor="#39CCCC">

                      <div class="text-white">Online</div>
                    </div>
                    <!-- ./col -->
                    <div class="col-4 text-center">
                      <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                        data-fgColor="#39CCCC">

                      <div class="text-white">In-Store</div>
                    </div>
                    <!-- ./col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </section> --}}
            <!-- right col -->
          </div>
          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
      <script>
        // var ctx = document.getElementById('myChart').getContext('2d');
        // var myChart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        //         datasets: [{
        //             label: '# of Votes',
        //             data: [12, 19, 3, 5, 2, 3],
        //             backgroundColor: [
        //                 'rgba(255, 99, 132, 0.2)',
        //                 'rgba(54, 162, 235, 0.2)',
        //                 'rgba(255, 206, 86, 0.2)',
        //                 'rgba(75, 192, 192, 0.2)',
        //                 'rgba(153, 102, 255, 0.2)',
        //                 'rgba(255, 159, 64, 0.2)'
        //             ],
        //             borderColor: [
        //                 'rgba(255, 99, 132, 1)',
        //                 'rgba(54, 162, 235, 1)',
        //                 'rgba(255, 206, 86, 1)',
        //                 'rgba(75, 192, 192, 1)',
        //                 'rgba(153, 102, 255, 1)',
        //                 'rgba(255, 159, 64, 1)'
        //             ],
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         scales: {
        //             yAxes: [{
        //                 ticks: {
        //                     beginAtZero: true
        //                 }
        //             }]
        //         }
        //     }
        // });

        // var year = <?php echo $year; ?>;
        // var user = <?php echo $user; ?>;
        // var barChartData = {
        //     labels: year,
        //     datasets: [{
        //         label: 'User',
        //         backgroundColor: "pink",
        //         data: user
        //     }]
        // };

        // window.onload = function() {
        //     var ctx = document.getElementById("myChart").getContext("2d");
        //     window.myBar = new Chart(ctx, {
        //         type: 'bar',
        //         data: barChartData,
        //         options: {
        //             elements: {
        //                 rectangle: {
        //                     borderWidth: 2,
        //                     borderColor: '#c1c1c1',
        //                     borderSkipped: 'bottom'
        //                 }
        //             },
        //             responsive: true,
        //             title: {
        //                 display: true,
        //                 text: 'Yearly User Joined'
        //             }
        //         }
        //     });
        // };

        var year = <?php echo $year; ?>;
        var user = <?php echo $user; ?>;
        var barChartData = {
          labels: year,
          datasets: [
            {
              //label: 'User',
              backgroundColor: "#007bff",
              borderColor: "#007bff",
              data: user
            },
            {
              //label: 'User',
              backgroundColor: "#ced4da",
              borderColor: "#ced4da",
              data: user
            }
          ]
        };

        $(function () {
          "use strict";
          var ticksStyle = { fontColor: "#495057", fontStyle: "bold" };
          var mode = "index";
          var intersect = true;
          var $salesChart = $("#myChart");
          var salesChart = new Chart($salesChart, {
            type: "bar",
            data: barChartData,
            // data: {
            //   labels: ["JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
            //   datasets: [
            //     {
            //       backgroundColor: "#007bff",
            //       borderColor: "#007bff",
            //       data: [1000, 2000, 3000, 2500, 2700, 2500, 3000],
            //     },
            //     {
            //       backgroundColor: "#ced4da",
            //       borderColor: "#ced4da",
            //       data: [700, 1700, 2700, 2000, 1800, 1500, 2000],
            //     },
            //   ],
            // },
            options: {
              maintainAspectRatio: false,
              tooltips: { mode: mode, intersect: intersect },
              hover: { mode: mode, intersect: intersect },
              legend: { display: false },
              scales: {
                yAxes: [
                  {
                    gridLines: {
                      display: true,
                      lineWidth: "4px",
                      color: "rgba(0, 0, 0, .2)",
                      zeroLineColor: "transparent",
                    },
                    ticks: $.extend(
                      {
                        beginAtZero: true,
                        callback: function (value) {
                          if (value >= 1000) {
                            value /= 1000;
                            value += "k";
                          }
                          return "$" + value;
                        },
                      },
                      ticksStyle
                    ),
                  },
                ],
                xAxes: [
                  { display: true, gridLines: { display: false }, ticks: ticksStyle },
                ],
              },
            },
          });
        });

        </script>