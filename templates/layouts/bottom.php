			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link" href="https://htd-official.com">
									Hamzah Tech Development
								</a>
							</li>
						</ul>
					</nav>
					<div class="copyright ml-auto">
						Copyright &copy; 2021, made with <i class="fa fa-heart heart text-danger"></i> by <a href="https://www.htd-official.com">HTD</a>
					</div>				
				</div>
			</footer>
		</div>
		<!-- End Custom template -->
	</div>
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>

	<!-- jQuery UI -->
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


	<!-- Chart JS -->
	<script src="assets/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Bootstrap Notify -->
	<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- Sweet Alert -->
	<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Atlantis JS -->
	<script src="assets/js/atlantis.min.js"></script>

	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<script src="assets/js/setting-demo.js"></script>
	<script src="assets/js/demo.js"></script>
	<script>
		Circles.create({
			id:'circles-1',
			radius:45,
			value:60,
			maxValue:100,
			width:7,
			text: 5,
			colors:['#f1f1f1', '#FF9E27'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		Circles.create({
			id:'circles-2',
			radius:45,
			value:70,
			maxValue:100,
			width:7,
			text: 36,
			colors:['#f1f1f1', '#2BB930'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		Circles.create({
			id:'circles-3',
			radius:45,
			value:40,
			maxValue:100,
			width:7,
			text: 12,
			colors:['#f1f1f1', '#F25961'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

		var mytotalIncomeChart = new Chart(totalIncomeChart, {
			type: 'bar',
			data: {
				labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
				datasets : [{
					label: "Total Income",
					backgroundColor: '#ff9e27',
					borderColor: 'rgb(23, 125, 255)',
					data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
				}],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					display: false,
				},
				scales: {
					yAxes: [{
						ticks: {
							display: false //this will remove only the label
						},
						gridLines : {
							drawBorder: false,
							display : false
						}
					}],
					xAxes : [ {
						gridLines : {
							drawBorder: false,
							display : false
						}
					}]
				},
			}
		});

		$('#lineChart').sparkline([105,103,123,100,95,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#ffa534',
			fillColor: 'rgba(255, 165, 52, .14)'
		});
	</script>
	<script>
	window.table_row_selected = -1
	window.onkeyup = event => {
		var key = event.key
		if(key == 'F1')
			location.href = 'index.php?r=pos/index'
		if(key == 'F2')
			window.open('index.php?r=pos/index')
		if(key == 'F3')
		{
			document.querySelector('input[name=payment_total]').value = ''
			document.querySelector('input[name=payment_total]').focus()
		}
		if(key == 'F4')
		{
			document.querySelector('#input-kode').focus()
		}
		if(key == 'F5')
		{
			document.querySelector('#kode-kustomer').focus()
		}

		var charCode = (event.which) ? event.which : event.keyCode;
        if (event.ctrlKey && ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105))) {
            window.table_row_selected = parseInt(key)-1
			tableHighlight()
        }

		if(window.table_row_selected > -1)
		{
			var sel = window.table_row_selected
			if(event.keyCode == 8 && document.querySelector('#q-'+sel) !== document.activeElement)
			{
				var id  = document.querySelector('#data-'+sel).dataset.id
				deleteTransaction(id)
				window.table_row_selected = -1
			}

			if(key == 'q')
			{
				document.querySelector('#q-'+sel).focus()
			}
		}
	}

	function tableHighlight()
	{
		var sel = window.table_row_selected
		document.querySelectorAll('.data-row').forEach(row => row.style.background = '#FFF')
		document.querySelector('#data-'+sel).style.background = '#efefef'
		
	}
	</script>
</body>
</html>