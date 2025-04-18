<html>
<head>
    <title>Struk Pembelian</title>
		<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 11px;
			width: 58mm;
			margin: auto;
		}
		.text-center {
			text-align: center;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 10px;
		}
		td, th {
			padding: 3px 0;
		}
		.line {
			border-bottom: 1px dashed #000;
			margin: 5px 0;
		}
		.totals {
			margin-top: 10px;
		}
		@media print {
			body {
                width: auto;
                margin: 0;
            }
		}
	</style>

		</head>
		<body onload="window.print()">
		<?php 
		@ob_start();
		session_start();
		if (!empty($_SESSION['admin'])) {} else {
			echo '<script>window.location="login.php";</script>';
			exit;
		}
		require 'koneksi.php';
		include $view;
		$lihat = new view($config);

		$toko = $lihat->toko() ?: ['nama_toko' => '-', 'alamat_toko' => '-'];
		$hsl = $lihat->penjualan() ?: [];

		$total_sebelum_diskon = 0;
		$total_setelah_diskon = 0;
		?>

		<div class="text-center">
			<strong><?php echo $toko['nama_toko']; ?></strong><br>
			<?php echo $toko['alamat_toko']; ?><br>
			Tanggal: <?php echo date("j F Y, G:i"); ?><br>
			Kasir: <?php echo htmlentities($_GET['nm_member'] ?? '-'); ?>
		</div>

		<div class="line"></div>

		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>Barang</th>
					<th>Jml</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$no = 1;
				foreach ($hsl as $isi) {
					$diskon = $isi['diskon'] ?? 0;
					$harga_sebelum = $isi['total'];
					$harga_setelah = $harga_sebelum - ($diskon / 100 * $harga_sebelum);
					$total_sebelum_diskon += $harga_sebelum;
					$total_setelah_diskon += $harga_setelah;
				?>
				<tr>
					<td><?php echo $no++; ?></td>
					<td><?php echo $isi['nama_barang']; ?></td>
					<td><?php echo $isi['jumlah']; ?></td>
					<td><?php echo number_format($isi['total']); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

		<div class="line"></div>

		<div class="totals">
			<table>
				<tr>
					<td>Total Sebelum Diskon</td>
					<td align="right">Rp. <?php echo number_format($total_sebelum_diskon); ?></td>
				</tr>
				<tr>
					<td>Diskon</td>
					<td align="right"><?php echo ($_GET['diskon'] ?? 0); ?>%</td>
				</tr>
				<tr>
					<td>Total Setelah Diskon</td>
					<td align="right">Rp. <?php echo number_format($_GET['total_akhir'] ?? $total_setelah_diskon); ?></td>
				</tr>
				<tr>
					<td>Bayar</td>
					<td align="right">Rp. <?php echo number_format($_GET['bayar'] ?? 0); ?></td>
				</tr>
				<tr>
					<td>Kembali</td>
					<td align="right">Rp. <?php echo number_format($_GET['kembali'] ?? 0); ?></td>
				</tr>
			</table>
		</div>

		<div class="line"></div>

		<div class="text-center">
			Terima Kasih Telah Berbelanja di Toko Kami!
		</div>

		</body>
		</html>
