<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Pesanan Pelanggan</title>

		<style>
            .invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 15px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 10px;
				line-height: 15px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 3px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: left;

			}

			.invoice-box table tr.top table td {
				padding-bottom: 10px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 15px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 5px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 1px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		.style1 {
	font-size: 12px;
	font-weight: bold;
}
.style5 {font-size: 9px}
        .style9 {font-size: 9px; line-height: 15px; margin-top: 1 }
.style12 {font-size: 14px}
.style15 {
	color: #FF0000;
	font-size: 10;
}
        .style16 {
	font-size: 10px;
	font-style: italic;
}
.style18 {font-size: 10px;}
.style20 {font-size: 12; line-height: 50% }
        </style>
</head>

<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2" class="mt-0 mb-0">
						<table class="top mt-0 pt-0">
							<tr>
								<td width="35%">
                                    <div align="center">
                                        <img src="{{ $baseurl }}" width="160" height="90"><br>
                                    </div>
                                </td>
								<td width="40%">
                                    <p style="line-height: 10px">APARTEMENT PURI PARKVIEW<br>
                                    TOWER AAA / LT.1 / 16<br>
                                    Jl. Pesanggrahan Raya No.88<br>
                                    Meruya Utara, Kembangan<br>
                                    Jakarta Barat 11620 - Indonesia<br>
                                    Telpon:	021	-	5890	2704<br>
                                    Customer	Service:	<strong>08588	177	177 9</strong></p>
                                </td>
                                <td width="25%">
                                    <div align="center"><img src="{{ $baseurl2 }}" width="165" height="65"><br>
                                    </div>
                                </td>
							</tr>
						</table>
                    </td>
				</tr>
				<tr class="heading" >
					<td colspan="2">
                        <div align="center" >Formulir Pendaftaran Pelanggan</div>
                    </td>
				</tr>
				<tr class="item">
					<td width="59%"><div align="center"><strong>Nomor: {{ $order->no_formulir }}</strong></div></td>

					<td width="41%"><div align="left"><strong>Tanggal: {{ Carbon\Carbon::parse($order->tgl_order)->format('d-M-Y') }}</strong></div></td>
				</tr>
				<tr class="heading">
					<td>
                        <div align="left" >Data Pelanggan</div>
                    </td>
				    <td>
                        <div align="center" >Nomor Pelanggan: {{ $order->pelanggan->no_pelanggan }}</div>
                    </td>
				</tr>
				<tr class="item">
					<td colspan="2">
						<table border="1" class="details">
							<tr class="item">
                                <td width="40%" class="item"><span >Nama Pelanggan</span></td>
						        <td colspan="2" class="items " >{{ $order->pelanggan->nama_lengkap }}</td>
							</tr>
							<tr class="item">
                                <td >No. Identitas (KTP/SIM/PASPORT)</td>
							    <td colspan="2">{{ $order->pelanggan->nomer_identitas }}</td>
                            </tr>
							<tr class="item">
                                <td class="item"><span >Tower / Unit</span></td>
							    <td width="32%">{{ $order->pelanggan->sub_tower."/".$order->pelanggan->lantai."/".$order->pelanggan->nomer_unit }}</td>
							<td width="36%"><span >Status Pemilik / Penyewa</span> </td>
							</tr>
							<tr class="item">
                                <td class="item"><span >Alamat Sesuai KTP</span></td>
							    <td colspan="2">{{ $order->pelanggan->alamat_identitas }}</td></tr>
							<tr class="item">
                                <td class="item"><span >No. Telpon</span></td>
							    <td>{{ $order->pelanggan->nomer_hp }}</td>
							    <td><span >No. HP/Whatsapp: {{ $order->pelanggan->nomer_hp }}</span></td>
							</tr>
							<tr class="item">
                                <td class="item"><span>Email</span></td>
							    <td colspan="2">{{ $order->pelanggan->email }}</td>
                            </tr>
						</table>
                    </td>
				</tr>
				<tr class="item">
                    <td colspan="2">
                        <table border="1" class="item">
                            <tr class="heading">
                                <td><div align="center">Jenis Layanan</div></td>
                                <td><div align="center">Harga Layanan</div></td>
                                <td><div align="center">Periode</div></td>
                                <td><div align="center">Promo/Diskon</div></td>
                                <td><div align="center">Total Harga</div></td>
                            </tr>
                            <?php
                            $ppn=0;
                            $gtot_amount=0;
                            ?>
                            @foreach ($order_dtl as $data )
                                @if ($data->layanan_id <>'10' & $data->layanan_id <>'11' )
                                <tr class="item">
                                    <td>{{ $data->title }}</td>
                                    <td ><div align="right">{{ number_format($data->amount) }}</div></td>
                                    <td align="center">{{ $data->qty }}</td>
                                    <td align="right">{{ number_format($data->diskon) }}</td>
                                    @if ($data->trx_order_id<=400)
                                    <td align="right">{{ number_format($data->sub_amount) }}</td>

                                    @else
                                    <td align="right">{{ number_format($data->sub_amount-$data->tax_amount) }}</td>

                                    @endif
                                </tr>
                                @elseif($data->layanan_id =='11')
                                <tr>
                                    <td colspan="4">{{ $data->title }}</td>
                                    <td ><div align="right">{{ number_format($data->sub_amount) }}</div></td>
                                </tr>
                                @endif
                                <?php
                                $ppn += $data->tax_amount;
                                $gtot_amount += $data->sub_amount-$data->tax_amount;
                                ?>
                            @endforeach
                        </table>
                    </td>
				</tr>
				<tr class="items">
                    <td>
                        Periode Berlangganan: <br>
                        Mulai : {{ Carbon\Carbon::parse($order->tgl_rencana_belangganan)->format('d-M-Y') }}
                         <br>
                        Pilih :
                        @if ($order->langganan_status=='1')
                        <input type="checkbox" id="bulanan" name="bulanan" value="bulanan" checked>
                        <label for="bulanan"> Bulanan</label>
                        <input type="checkbox" id="tahunan" name="tahunan" value="tahunan" >
                        <label for="tahunan"> Tahunan</label>
                        @else
                        <input type="checkbox" id="bulanan" name="bulanan" value="bulanan" >
                        <label for="bulanan"> Bulanan</label>
                        <input type="checkbox" id="tahunan" name="tahunan" value="tahunan" checked>
                        <label for="tahunan"> Tahunan</label>
                        @endif
                         <span class="style15"><br>
                        <span class="style16"><p style="line-height:15px">
                            *Deposit akan dikembalikan 14 (Empat Belas) hari setelah langganan berakhir.<br>
                            Promo berlaku untuk 1 periode</p></span></span>
                    </td>
					<td>
						<table border="1">
							{{--  <tr class="item">
							  <td width="54%"><div align="left">PPN 11%</div></td>
								<td width="46%"><div align="right">{{ number_format($ppn) }}</div></td>
							</tr>  --}}
							{{--  <tr class="item">
								<td><div align="left">Deposit Include PPN*</div></td>
								<td><div align="right"> </div></td>
							</tr>  --}}
							<tr class="item">
								<td><div align="left"><strong>GRAND TOTAL</strong></div></td>
								<td><div align="right">{{ number_format($gtot_amount+$ppn) }}</div></td>
							</tr>
                        </table>
                    </td>
				</tr>
				<tr class="heading" >
					<td>JENIS PEMBAYARAN </td>
					<td>&nbsp;</td>
				</tr>
				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td width="60%">
                                    <form name="form1" method="post" action="">
                                        <label>QRIS</label>
                                        <input type="checkbox" name="checkbox" value="checkbox">&nbsp;&nbsp;&nbsp;
                                        <label>Kartu Kredit</label>
                                        <input type="checkbox" name="checkbox" value="checkbox">&nbsp;&nbsp;&nbsp;
                                        <label>Debit</label>
                                        <input type="checkbox" name="checkbox" value="checkbox">
                                    </form>
                                </td>
								<td width="40%">
                                    Transfer : Rekening BCA PT. Media Prima Jaringan<br>
                                    No. Rek : 4899 77 2005<br>
                                    Bukti Transfer kirim ke: <strong>0857 27322 2732</strong>
                                </td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
                                <td width="50%"><div align="center">Pelanggan</div></td>
                                <td width="50%"><div align="center">Disetujui Oleh:<br>PT. Media Prima Jaringan</div></td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="center">(......................)<br>Tanggal diterima</div>
                                </td>
                                <td>
                                    <div align="center">(......................)<br>Tanggal diterima</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="style18"><p style="line-height:15px">
                                        - Layanan Internet di Apartemen Puri Parkview adalah bentuk kerjasama antara PT. Media Prima Jaringan dengan PT. Mora Telematika Indonesia (Moratelindo) sebagai ekslusif ISP dengan merek layanan yaitu Oxygen.id<br>
                                        - Layanan Fixed Telephony di Apartemen Puri Parkview yang disediakan merupakan bentuk Kerjasama antara PT. Smartfren Telecom Tbk. dengan PT. Mora Telematika Indonesia (Moratelindo)</p>
                                    </span>
                                </td>
                            </tr>
						</table>
					</td>
				</tr>
                <tr class="heading" >
					<td colspan="2">
                        <table style="padding-top: 0%">
                            <tr class="heading">
                                <td><span>Lembar 1: Pelanggan</span></td>
                                <td><span>Lembar 2: Kasir</span></td>
                                <td><span>Lembar 3: Akunting</span></td>
                            </tr>
                        </table>
                    </td>
				</tr>
			</table>
		</div>
</body>
</html>
