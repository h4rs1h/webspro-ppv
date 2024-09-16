<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Kwitansi</title>

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
            .capitalize {
                text-transform: capitalize;
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
                        <div align="center" ><h1>TANDA TERIMA PEMBAYARAN</h1></div>
                    </td>
				</tr>
				<tr class="item">
                    <td colspan="2">
                        <table border="0">
                            @if ($bayar->tipe_bayar=='1')
                                <tr>
                                    <td>Nomer Pemesanan</td>
                                    <td>:</td>
                                    <td>{{ $bayar->no_formulir }}</td>
                                    <td>Tanggal Pemesanan</td>
                                    <td>:</td>
                                    <td>{{ Carbon\Carbon::parse($bayar->tgl_order)->format('d-M-Y') }}</td>
                                </tr>

                            @else
                                <tr>
                                    <td>Nomer Tagihan</td>
                                    <td>:</td>
                                    <td>{{ $bayar->no_formulir }}</td>
                                    <td>Tanggal Tagihan</td>
                                    <td>:</td>
                                    <td>{{ Carbon\Carbon::parse($bayar->tgl_order)->format('d-M-Y') }}</td>
                                </tr>

                            @endif
                            <tr>
                                <td>Nomer Pembayaran</td>
                                <td>:</td>
                                <td>{{ $bayar->nomer_bayar }}</td>
                                <td>Tanggal Pembayaran</td>
                                <td>:</td>
                                <td>{{ Carbon\Carbon::parse($bayar->tgl_bayar)->format('d-M-Y') }}</td>
                            </tr>
                            <tr>
                                <td>Nomer Pelanggan</td>
                                <td>:</td>
                                <td>{{ $bayar->no_pelanggan }} - ({{ $bayar->unitid }})</td>
                                @if ($bayar->tipe_bayar=='1')
                                <td>Periode Pemakaian</td>
                                <td>:</td>
                                @if (!empty($periode))
                                    @foreach ($periode as $d)
                                        <td>{{ $d->pemakaian }}</td>
                                    @endforeach
                                @else
                                    <td>-</td>
                                @endif
                                @else
                                <td>Periode Pemakaian</td>
                                <td>:</td>
                                <td>{{ $tagihan->periode_pemakaian }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Telah terima dari</td>
                                <td>:</td>
                                <td>{{ $bayar->nama_lengkap }}</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Sejumlah uang</td>
                                <td>:</td>
                                <td>Rp {{ number_format($bayar->amount) }}</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Teribilang</td>
                                <td>:</td>
                                <td colspan="4" class="capitalize">{{ $bayar->terbilang }}</td>

                            </tr>

                        </table>
                    </td>
				</tr>
                <tr class="details">
                    <td colspan="2"></td>
                </tr>
                <tr class="details">
                    <td colspan="2">
                        <table border="1">
                            <tr>
                                <td width="5%">No</td>
                                <td width="70%" align="center">Keterangan</td>
                                <td width="20%" align="center">Jumlah</td>
                            </tr>
                            @foreach ($order_dtl as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->title }}</td>
									<td>Rp {{$data->payment_amount}}</td>
                                  <!--  <td >Rp {{ number_format(($data->harga*0.11)+$data->harga) }}</td>-->
                                </tr>
                            @endforeach
                            <tr class="total">
                                <td colspan="2" align="center"><strong>Total</strong></td>
                                <td ><strong>Rp {{ number_format($bayar->amount) }}</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="items">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td  width="15%" >Total Tagihan</td>
                                <td  width="2%">:</td>
                                <td  width="15%">Rp
                                    @if ($bayar->status_bayar=='1')
                                        {{ number_format($bayar->amount) }}
                                    @else
                                        {{ number_format($bayar->gtot_amount) }}

                                    @endif
                                </td>
                                <td  width="2%"></td>
                                <td  >Perhatian: </td>
                                <td  width="10%">{{ Carbon\Carbon::parse( $bayar->tgl_bayar)->format('d-M-Y') }}</td>
                            </tr>
                            <tr>
                                <td  width="15%" >Pembayaran</td>
                                <td  width="2%">:</td>
                                <td  width="15%">Rp {{ number_format($bayar->amount) }}</td>
                                <td  width="2%"></td>
                                <td  >&nbsp;</td>
                                <td  width="10%">&nbsp;</td>
                            </tr>
							@if ($bayar->tipe_bayar == '1')
                            <tr>
                                <td width="15%">Total Pembayaran</td>
                                <td width="2%">:</td>
                                <td width="15%" >Rp {{ number_format($bayar->totalamountpay) }}</td>
                                <td width="2%"></td>
                                <td>&nbsp;</td>
                                <td width="10%">&nbsp;</td>
                            </tr>
                        @endif
                            <tr>
                                <td  width="15%" >Sisa Pembayaran</td>
                                <td  width="2%">:</td>
                                <td  width="15%">Rp
                                    @if ($bayar->status_bayar=='1')
                                        0
                                    @else
                                       @if ($bayar->tipe_bayar == '1')
                                            {{ number_format($bayar->gtot_amount - $bayar->totalamountpay) }}
                                        @else
                                            {{ number_format($bayar->gtot_amount - $bayar->amount) }}
                                        @endif

                                    @endif
                                </td>
                                <td  width="2%"></td>
                                <td  ></td>
                                <td  width="10%"></td>
                            </tr>
                            <tr>
                                <td  width="15%" >Status</td>
                                <td  width="2%">:</td>
                                <td  width="15%">
                                    @if ($bayar->status_bayar=='1')
                                        <strong>Lunas</strong>
                                    @else
                                        <strong>Belum Lunas</strong>
                                    @endif

                                </td>
                                <td  width="2%"></td>
                                <td  ></td>
                                <td  width="10%"></td>
                            </tr>
                            <tr>
                                <td  width="15%" >Jatuh Tempo</td>
                                <td  width="2%">:</td>
                                <td  width="15%"></td>
                                <td  width="2%"></td>

                                <td  colspan="2" align="right">PT. Media Prima Jaringan</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><h3>
                        <strong>
                            Note: Tanda Terima Pembayaran sebagai kwitansi sementara dan akan digantikan dengan Kwitansi asli oleh finance setelah dana masuk ke rekening PT. Media Prima Jaringan.  </td>

                        </strong>
                    </h3>
                </tr>
			</table>
		</div>
</body>
</html>
