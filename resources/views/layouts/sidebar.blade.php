<!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                @if ($level=='0')
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="/home" class="{{ Request::is('/home*') ? 'active' : ''}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a class="nav-link {{ Request::is('pelanggan/myprofile*') ? 'active' : ''}}" href="/pelanggan/myprofile/{{ auth()->user()->id }}">
                         <i class="fa fa-user fa-fw"></i>  My Profile
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ Request::is('pelanggan/layanan*') ? 'active' : ''}}" href="/pelanggan/layanan/{{ auth()->user()->id }}">
                         <i class="fa fa-share fa-fw"></i>  Layanan
                        </a>
                    </li>

                    <!-- <li>
                        <a href="#" class="{{ Request::is('/produk*') ? 'active' : ''}}"><i class="fa fa-sitemap fa-fw"></i> List Layanan<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/layanan/Internet">Internet</a>
                            </li>
                            <li>
                                <a href="/layanan/tv">TV Extream</a>
                            </li>
                            <li>
                                <a href="/layanan/telephony">Telephony</a>
                            </li>
                        </ul>

                    </li>-->
                    <li>

                        <a class="nav-link {{ Request::is('pelanggan/billing*') ? 'active' : ''}}" href="/pelanggan/billing/{{ auth()->user()->id }}">
                            <i class="fa fa-table fa-fw"></i>  Billing
                           </a>
                    </li>
                    {{--  <li>
                        <a href="/billing/{{ Auth::user()->id }}"><i class="fa fa-edit fa-fw"></i> Billing </a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Status Order</a>
                    </li>  --}}



                </ul>
				{{--  Level Teknisi  --}}
                @elseif ($level=='1')
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="/home" class="{{ Request::is('/home*') ? 'active' : ''}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-book fa-fw"></i> Master Data<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/layanan" class="{{ Request::is('/layanan*') ? 'active' : ''}}"><i class="fa fa-sitemap fa-fw"></i> Produk / Layanan</a>
                            </li>
                            <li>
                                <a href="/admin/pelanggan" class="{{ Request::is('/pelanggan*') ? 'active' : ''}}"><i class="fa fa-user-circle fa-fw"></i> Pelanggan</a>
                            </li>
                            {{--  <li>
                                <a href="/admin/users" class="{{ Request::is('/user*') ? 'active' : ''}}"><i class="fa fa-users fa-fw"></i> Data User</a>
                            </li>  --}}
                        </ul>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-line-chart fa-fw"></i> Transaksi<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            {{--  <li>
                                <a href="/admin/trx_order"  class="{{ Request::is('/trxorder*') ? 'active' : ''}}"><i class="fa fa-cubes fa-fw"></i> Order Layanan</a>
                            </li>  --}}
                            <li>
                                <a href="/admin/trx_aktivasi"  class="{{ Request::is('/trxaktivasi*') ? 'active' : ''}}"><i class="fa fa-cogs fa-fw"></i> Aktivasi Layanan</a>
                            </li>
                            {{--  <li>
                                <a href="/admin/trx_invoice"  class="{{ Request::is('/trxinvoice*') ? 'active' : ''}}"><i class="fa fa-usd fa-fw"></i> Tagihan Invoice</a>
                            </li>  --}}
                            {{--  <li>
                                <a href="/admin/trx_bayar" class="{{ Request::is('/trxbayar*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Pembayaran</a>
                            </li>  --}}
                            <li>
                                <a href="/admin/trx_nonaktif" class="{{ Request::is('/trxnonaktif*') ? 'active' : ''}}"><i class="fa fa-power-off fa-fw"></i> Non Aktif Layanan</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-support fa-fw"></i> Help Desk<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <li>
                                <a href="/admin/trx_aktifasi"  class="{{ Request::is('/trxaktifasi*') ? 'active' : ''}}"><i class="fa fa-ticket fa-fw"></i> Keluhan Pelanggan</a>
                            </li>


                        </ul>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-sitemap fa-fw"></i> Laporan<span class="fa arrow"></span></a>
                        {{--  <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/sales-admin"  class="{{ Request::is('/admin/sales-admin*') ? 'active' : ''}}"><i class="fa fa-ticket fa-fw"></i> Admin Sales</a>

                            </li>
                            <li>
                                <a href="/admin/akunting" class="{{ Request::is('/admin/akunting*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Accounting</a>
                            </li>
                            <li>
                                <a href="/admin/monitoring" class="{{ Request::is('/admin/monitoring*') ? 'active' : ''}}"><i class="fa fa-list fa-fw"></i> Monitoring</a>
                            </li>

                        </ul>  --}}
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-sitemap fa-fw"></i> Whatsapp Blast<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/wa/blast"  class="{{ Request::is('/admin/wa/blast*') ? 'active' : ''}}"><i class="fa  fa-volume-up fa-fw"></i>Blast Info</a>

                            </li>
                            <li>
                                <a href="/admin/wa/setting?wablast=statusqr"  class="{{ Request::is('/admin/wa/setting*') ? 'active' : ''}}"><i class="fa fa-ticket fa-fw"></i> Setting</a>

                            </li>
                            <li>
                                <a href="/admin/wa/outbox" class="{{ Request::is('/admin/wa/outbox*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Outbox</a>
                            </li>
                            <li>
                                <a href="/admin/wa/sending?getdata=all" class="{{ Request::is('/admin/wa/sending*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Sending</a>
                            </li>

                        </ul>
                    </li>
                </ul>
				{{--  Level Marketing  --}}
        @elseif ($level == '2')
            <ul class="nav" id="side-menu">
                <li>
                    <a href="/home" class="{{ Request::is('/home*') ? 'active' : '' }}"><i
                            class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-book fa-fw"></i> Master Data<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/layanan" class="{{ Request::is('/layanan*') ? 'active' : '' }}"><i
                                    class="fa fa-sitemap fa-fw"></i> Produk / Layanan</a>
                        </li>
                        <li>
                            <a href="/admin/pelanggan" class="{{ Request::is('/pelanggan*') ? 'active' : '' }}"><i
                                    class="fa fa-user-circle fa-fw"></i> Pelanggan</a>
                        </li>
                        {{--  <li>
                                <a href="/admin/users" class="{{ Request::is('/user*') ? 'active' : ''}}"><i class="fa fa-users fa-fw"></i> Data User</a>
                            </li>  --}}
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-line-chart fa-fw"></i> Transaksi<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/trx_order" class="{{ Request::is('/trxorder*') ? 'active' : '' }}"><i
                                    class="fa fa-cubes fa-fw"></i> Order Layanan</a>
                        </li>
                        <li>
                            <a href="/admin/trx_aktivasi" class="{{ Request::is('/trxaktivasi*') ? 'active' : '' }}"><i
                                    class="fa fa-cogs fa-fw"></i> Aktivasi Layanan</a>
                        </li>
                        {{--  <li>
                                <a href="/admin/trx_invoice"  class="{{ Request::is('/trxinvoice*') ? 'active' : ''}}"><i class="fa fa-usd fa-fw"></i> Tagihan Invoice</a>
                            </li>  --}}
                        {{--  <li>
                                <a href="/admin/trx_bayar" class="{{ Request::is('/trxbayar*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Pembayaran</a>
                            </li>  --}}
                        <li>
                            <a href="/admin/trx_nonaktif"
                                class="{{ Request::is('/trxnonaktif*') ? 'active' : '' }}"><i
                                    class="fa fa-power-off fa-fw"></i> Non Aktif Layanan</a>
                        </li>

                    </ul>
                </li>
                {{-- <li>
                    <a href="#"><i class="fa fa-support fa-fw"></i> Help Desk<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">

                        <li>
                            <a href="/admin/trx_aktifasi"
                                class="{{ Request::is('/trxaktifasi*') ? 'active' : '' }}"><i
                                    class="fa fa-ticket fa-fw"></i> Keluhan Pelanggan</a>
                        </li>


                    </ul>
                </li> --}}
                <li>
                    <a href="#"><i class="fa fa-sitemap fa-fw"></i> Laporan<span class="fa arrow"></span></a>
                    {{--  <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/sales-admin"  class="{{ Request::is('/admin/sales-admin*') ? 'active' : ''}}"><i class="fa fa-ticket fa-fw"></i> Admin Sales</a>

                            </li>
                            <li>
                                <a href="/admin/akunting" class="{{ Request::is('/admin/akunting*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Accounting</a>
                            </li>
                            <li>
                                <a href="/admin/monitoring" class="{{ Request::is('/admin/monitoring*') ? 'active' : ''}}"><i class="fa fa-list fa-fw"></i> Monitoring</a>
                            </li>

                        </ul>  --}}
                </li>
                {{-- <li>
                    <a href="#"><i class="fa fa-sitemap fa-fw"></i> Whatsapp Blast<span
                            class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/wa/blast"
                                class="{{ Request::is('/admin/wa/blast*') ? 'active' : '' }}"><i
                                    class="fa  fa-volume-up fa-fw"></i>Blast Info</a>

                        </li>
                        <li>
                            <a href="/admin/wa/setting?wablast=statusqr"
                                class="{{ Request::is('/admin/wa/setting*') ? 'active' : '' }}"><i
                                    class="fa fa-ticket fa-fw"></i> Setting</a>

                        </li>
                        <li>
                            <a href="/admin/wa/outbox"
                                class="{{ Request::is('/admin/wa/outbox*') ? 'active' : '' }}"><i
                                    class="fa fa-bank fa-fw"></i> Outbox</a>
                        </li>
                        <li>
                            <a href="/admin/wa/sending?getdata=all"
                                class="{{ Request::is('/admin/wa/sending*') ? 'active' : '' }}"><i
                                    class="fa fa-bank fa-fw"></i> Sending</a>
                        </li>

                    </ul>
                </li> --}}
            </ul>
                @elseif ($level=='4')
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="/home" class="{{ Request::is('/home*') ? 'active' : ''}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-book fa-fw"></i> Master Data<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/layanan" class="{{ Request::is('/layanan*') ? 'active' : ''}}"><i class="fa fa-sitemap fa-fw"></i> Produk / Layanan</a>
                            </li>
                            <li>
                                <a href="/admin/pelanggan" class="{{ Request::is('/pelanggan*') ? 'active' : ''}}"><i class="fa fa-user-circle fa-fw"></i> Pelanggan</a>
                            </li>
                            <li>
                                <a href="/admin/users" class="{{ Request::is('/user*') ? 'active' : ''}}"><i class="fa fa-users fa-fw"></i> Data User</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-line-chart fa-fw"></i> Transaksi<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/trx_order"  class="{{ Request::is('/trxorder*') ? 'active' : ''}}"><i class="fa fa-cubes fa-fw"></i> Order Layanan</a>
                            </li>
                            <li>
                                <a href="/admin/trx_aktivasi"  class="{{ Request::is('/trxaktivasi*') ? 'active' : ''}}"><i class="fa fa-cogs fa-fw"></i> Aktivasi Layanan</a>
                            </li>
                            <li>
                                <a href="/admin/trx_invoice"  class="{{ Request::is('/trxinvoice*') ? 'active' : ''}}"><i class="fa fa-usd fa-fw"></i> Tagihan Invoice</a>
                            </li>
                            <li>
                                <a href="/admin/trx_bayar" class="{{ Request::is('/trxbayar*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Pembayaran</a>
                            </li>
 <li>
                                <a href="/admin/trx_nonaktif" class="{{ Request::is('/trxnonaktif*') ? 'active' : ''}}"><i class="fa fa-power-off fa-fw"></i> Non Aktif Layanan</a>
                            </li> 
                        </ul>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-support fa-fw"></i> Help Desk<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <li>
                                <a href="/admin/trx_aktifasi"  class="{{ Request::is('/trxaktifasi*') ? 'active' : ''}}"><i class="fa fa-ticket fa-fw"></i> Keluhan Pelanggan</a>
                            </li>


                        </ul>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-sitemap fa-fw"></i> Laporan<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/sales-admin"  class="{{ Request::is('/admin/sales-admin*') ? 'active' : ''}}"><i class="fa fa-ticket fa-fw"></i> Admin Sales</a>
                                {{--  <a href="#"  class="{{ Request::is('/order*') ? 'active' : ''}}"><i class="fa fa-cubes fa-fw"></i> Pelanggan</a>  --}}
                            </li>
                            <li>
                                <a href="/admin/akunting" class="{{ Request::is('/admin/akunting*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Accounting</a>
                            </li>
<li>
                                <a href="/admin/monitoring" class="{{ Request::is('/admin/monitoring*') ? 'active' : ''}}"><i class="fa fa-list fa-fw"></i> Monitoring</a>
                            </li>
                        </ul>
                    </li>
				<li>
                        <a href="#" ><i class="fa fa-whatsapp fa-fw"></i> Whatsapp Blast<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
							<li>
                                <a href="/admin/wa/blast"  class="{{ Request::is('/admin/wa/blast*') ? 'active' : ''}}"><i class="fa  fa-volume-up fa-fw"></i>Blast Info</a>

                            </li>
                            <li>
                                <a href="/admin/wa/setting?wablast=statusqr"  class="{{ Request::is('/admin/wa/setting*') ? 'active' : ''}}"><i class="fa fa-gear fa-fw"></i> Setting</a>

                            </li>
                            <li>
                                <a href="/admin/wa/outbox" class="{{ Request::is('/admin/wa/outbox*') ? 'active' : ''}}"><i class="fa fa-sign-out fa-fw"></i> Outbox</a>
                            </li>
                            <li>
                                <a href="/admin/wa/sending?getdata=all" class="{{ Request::is('/admin/wa/sending*') ? 'active' : ''}}"><i class="fa  fa-send fa-fw"></i> Sending</a>
                            </li>

                        </ul>
                    </li>
                </ul>
                @endif
            </div>
        </div>
