<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Question Test Admin</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
</head>
<body>

<div class="container-fluid" id="admin">
	<h3>Таблица результатов ответов</h3>
	<table id="answers-table" class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th>IP адрес</th>
				<th>Результат 1 вопроса</th>
				<th>Результат 2 вопроса</th>
				<th>Результат 3 вопроса</th>
				<th>Количество отправок теста</th>
				<th>Удалить</th>
			</tr>
		</thead>
	</table>

	<div id="chart-container" style="width: 600px; height: 400px; margin: auto">
		<canvas id="chart" width="200" height="160"></canvas>
	</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>


<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>


<script>
	$(function() {

		loadData();
		var answersTable, answersData, chart;

	    function loadData() {
            answersTable = $('#answers-table');
		    $.get('admin/loadAnswers', function(data) {
                answersData = data;
                answersTable.DataTable({
                    data: answersData,
                    columns: [
                        {data: 'ip_address'},
                        {data: 'q1'},
                        {data: 'q2'},
                        {data: 'q3'},
                        {data: 'count'},
                        {
                            data: 'ip_address',
	                        className: 'text-center',
                            "render": function (data, type, row, meta) {
                                return '<button class="btn btn-md btn-danger removeAnswer" data-index="'+meta.row+'" data-ip="'+data+'">Удалить</button>'
                            }
                        }
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    drawCallback: function() {
                        $('button.removeAnswer').on('click', removeAnswer);
                        showChart();
                    }
                });
		    });
	    }

	    function showChart() {
            var chartData = {
                q1: 0, q2: 0, q3: 0
            };
            answersData.map(function(answers) {
                chartData['q1'] += answers.q1;
                chartData['q2'] += answers.q2;
                chartData['q3'] += answers.q3;
            });

            chartData = Object.values(chartData);
            if(!chart) {
                chart = new Chart(document.getElementById("chart"), {
                    type: 'pie',
                    data: {
                        labels: ["Вопрос 1", "Вопрос 2", "Вопрос 3"],
                        datasets: [{
                            label: "Результаты ответов",
                            backgroundColor: ["#00008B", "#B22222", "#7FFF00"],
                            data: chartData
                        }]
                    }
                });
            } else {
                chart.data.datasets[0].data = chartData;
                chart.update();
            }
	    }

	    function removeAnswer(e) {
	        e.preventDefault();
	        if(confirm('Are you sure you wish to delete this answer?')) {
                var data = {ip: $(this).data('ip')};
                var index = $(this).data('index');
                $.post('admin/removeAnswer', data, function(response) {
                    if (response.success) {
                        e.target.closest('tr').remove();
                        answersData.splice(index, 1);
                        showChart();
                    } else {
                        alert(response.message);
                    }
                });
	        }
	    }
	});
</script>

</body>
</html>