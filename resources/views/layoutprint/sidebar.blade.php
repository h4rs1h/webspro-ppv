<!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                @if ($level=='0')
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="/home" class="{{ Request::is('/home*') ? 'active' : ''}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#" class="{{ Request::is('/produk*') ? 'active' : ''}}"><i class="fa fa-sitemap fa-fw"></i> Layanan<span class="fa arrow"></span></a>
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
                    </li>
                    <li>
                        <a href="/orders"><i class="fa fa-table fa-fw"></i> Order</a>
                    </li>
                    {{--  <li>
                        <a href="/billing/{{ Auth::user()->id }}"><i class="fa fa-edit fa-fw"></i> Billing </a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Status Order</a>
                    </li>  --}}
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
                                <a href="/admin/pembayaran" class="{{ Request::is('/pembayaran*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Pembayaran</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="#" ><i class="fa fa-sitemap fa-fw"></i> Laporan<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#"  class="{{ Request::is('/order*') ? 'active' : ''}}"><i class="fa fa-cubes fa-fw"></i> Pelanggan</a>
                            </li>
                            <li>
                                <a href="#" class="{{ Request::is('/pembayaran*') ? 'active' : ''}}"><i class="fa fa-bank fa-fw"></i> Transaksi</a>
                            </li>

                        </ul>
                    </li>

                </ul>
                @endif
            </div>
        </div>
