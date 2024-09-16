<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>{{ $title }}</title>

		<style>
            .invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 15px;
				border: 1px solid #eee;
				box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
				font-size: 12px;
				line-height: 15px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
                border-color: rgb(12, 12, 12);
                border-bottom: 1px;
                border-top: 1px;
                border-left: 1px;
                border-right: 1px;

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
				background: rgb(51, 29, 251);
				border-bottom: 1px solid #ddd;
				font-weight: bold;
                color:aliceblue;
                font-size: 14px
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
                                        <img src="{{ $baseurl }}"  width="160" height="90"><br>
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
                                    <div align="center">
                                        <img src="{{ $baseurl2 }}" width="165" height="65"><br>
                                    </div>
                                </td>
							</tr>
						</table>
                    </td>
				</tr>
                <tr>
                    <td width="60%">
                        <table border="1">
                            <tr>
                                <td>
                                    Nomor Tagihan /<i>Billing Number</i><br>
                                    Nomor Pelanggan /<i>Customer ID</i><br>
                                    {{--  Periode Pemakaian/<i>Usage Period</i><br>  --}}
                                    Tanggal Tagihan /<i>Billing Date</i><br>
                                    Tanggal Jatuh Tempo /<i>Due Date</i><br>
                                    Tagihan /<i>Current Changes (Rp)</i>
                                </td>
                                <td><div align="right">
                                    {{ $order->no_formulir }}<br>
                                    {{ $order->no_pelanggan }} - ({{ $order->unitid }})<br>
                                    {{--  {{ $tagihan->periode_pemakaian }}<br>  --}}
                                    {{ Carbon\Carbon::parse($order->tgl_order)->format('d-M-Y')  }}<br>
                                    {{ Carbon\Carbon::parse($order->tgl_aktivasi)->format('d-M-Y')  }}<br>
                                    {{ number_format($tagihan) }}
                                </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        Kepada:<br>
                        {{ $order->nama_lengkap }}<br>
                        Apartemen Puri Parkview<br>
                        Tower {{ $order->sub_tower }}<br>
                        {{ $order->alamat_identitas }}
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="2">
                        <div align="center" >Tagihan Invoice Pendaftaran</div>
                    </td>
                </tr>
                <tr class="item">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td><h3>Tagihan Sebelumnya<br>
                                Pembayaran Sebelumnya</h3></td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php $ppn=0;?>
                            @foreach ($order_dtl as $data )
                                <tr>
                                    <td>
                                        {{ $data->description }}<br>

                                    </td>
                                    <td>
                                        @if ($data->termin_bayar>0)
                                            <div align="right">{{ number_format($data->subtotal/$data->termin_bayar) }}</div>
                                            <?php
                                                $ppn = $ppn+$data->tax_amount/$data->termin_bayar;
                                            ?>
                                        @else
                                            <div align="right">{{ number_format($data->subtotal) }}</div>
                                            <?php
                                                $ppn = $ppn+$data->tax_amount;
                                            ?>
                                        @endif
                                    </td>
                                </tr>
                            
                            @endforeach
                            <tr>
                                <td>PPn 11%</td>
                                <td><div align="right">{{ number_format($ppn) }}</div> </td>
                            </tr>
                            <tr>
                                <td>
                                    Tagihan
                                </td>
                                <td>
                                    <div align="right">{{ number_format($subtagihan) }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   <h3>Total Tagihan</h3>
                                </td>

                                <td>
                                    <div align="right"><h3>{{ number_format($tagihan) }}</h3></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="heading">
                    <td colspan="2">
						@if ($termin>0)

                            <div align="left" ></div>
                        @else
                            <div align="left" >Terbilang: {{ ucwords($tbilang) }} Rupiah</div>

                        @endif
                        
                    </td>
                </tr>
                <tr class="items">
                    <td colspan="2">
                        <ul>
                            <li>
                                Pembayaran dapat dilakukan dengan cara transfer ke:<br>
                                Nama Rekening : Bank BCA<br>
                                Pemilik Rekening : PT. Media Prima Jaringan<br>
                                Nomor Rekening : 4899 77 2005
                            </li>
                            <li>
                                PT. Media Prima Jaringan hanya memiliki rekening yang tercantum di atas.
                            </li>
                            <li>
                                Masa aktif layanan Anda sesuai dengan periode berlangganan akan berakhir pada jam 00.000 WIB akhir periode
                            </li>
                            <li>
                                Layanan akan diputus jika belum melakukan pembayaran pada tanggal jatuh tempo
                            </li>
                            <li>
                                Harap dapat mengirimkan bukti transfer melalui WA ke nomor 0857-2732-2732 (Billing)
                            </li>
                            <li>Layanan akan aktif kembali setelah diterima bukti transfer</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <i>
                            Layanan Internet di Apartemen Puri Parkview adalah bentuk kerjasama antara PT. Media Prima Jaringan dengan PT. Mora Telematika Indonesia (Moratelindo) sebagai ekslusif ISP dengan merek layanan yaitu Oxygen.id<br>
                        </i>
                    </td>
                </tr>
            </table>

		</div>
</body>
</html>
