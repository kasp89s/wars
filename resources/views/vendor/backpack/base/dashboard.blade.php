@extends(backpack_view('blank'))

@section('content')
    <!-- MDB -->
    <script
        type="text/javascript"
        src="/assets/js/Chart.min.js"
    ></script>
    <div class="container-fluid">
        <div class="row">
            <canvas id="lineChart"></canvas>
        </div>
        <div class="row"></div>
        <div class="row">
                <div class="col-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-3 d-flex align-items-center">
                            <i class="la la-desktop bg-danger p-3 font-2xl mr-3"></i>
                            <div>
                                <div class="text-value-sm text-primary"><span id="receipt-total"></span> грн</div>
                                <div class="text-muted text-uppercase font-weight-bold small">Продано чеков</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
                <div class="col-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-3 d-flex align-items-center">
                            <i class="la la-apple bg-success p-3 font-2xl mr-3"></i>
                            <div>
                                <div class="text-value-sm text-info"><span id="bar-total"></span> грн</div>
                                <div class="text-muted text-uppercase font-weight-bold small">Бар</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-->

        </div>
    </div>
    <script type="text/javascript">
        window.addEventListener('load', (event) => {
            $.post(
                '/admin/get-main-info',
                {},
                function (response) {
                    drawGraph(response.recPerDay);
                    $('#receipt-total').text(response.recTotalAmountByDay);
                    $('#bar-total').text(response.barTotalAmountByDay);
                },
                'json'
            );
        });

        function drawGraph(recPerDay) {
            var ctxL = document.getElementById("lineChart").getContext('2d');
            var myLineChart = new Chart(ctxL, {
                type: 'line',
                data: {
                    labels: recPerDay.labels,
                    datasets: [{
                        label: "Игровое время",
                        data: recPerDay.data,
                        backgroundColor: [
                            'rgba(105, 0, 132, .2)',
                        ],
                        borderColor: [
                            'rgba(200, 99, 132, .7)',
                        ],
                        borderWidth: 2
                    },
                        {
                            label: "Бар",
                            data: [28, 48, 40, 19, 86, 27, 90],
                            backgroundColor: [
                                'rgba(0, 137, 132, .2)',
                            ],
                            borderColor: [
                                'rgba(0, 10, 130, .7)',
                            ],
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true
                }
            });
        }

    </script>
@endsection
