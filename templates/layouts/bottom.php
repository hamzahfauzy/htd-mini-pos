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
						Copyright &copy; <?= date('Y') ?> made with <i class="fa fa-heart heart text-danger"></i> by <a href="https://htd-official.com">HTD</a>
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

	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Atlantis JS -->
	<script src="assets/js/atlantis.min.js"></script>

	<script src="js/functions.js?version=1.1"></script>
	<script>
		$('.datatable').dataTable({
			responsive: true
		});
			
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