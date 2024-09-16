<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link {{ Request::is('pelanggan') ? 'active' : ''}}" aria-current="page" href="/pelanggan">
            <span data-feather="home"></span>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('pelanggan/myaccount*') ? 'active' : ''}}" href="/pelanggan/myaccount/{{ auth()->user()->id }}">
            <span data-feather="file-text"></span>
            My Account
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('pelanggan/order*') ? 'active' : ''}}" href="/pelanggan/order">
            <span data-feather="file-text"></span>
            Order
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('pelanggan/billing*') ? 'active' : ''}}" href="/pelanggan/billing">
            <span data-feather="file-text"></span>
            Billing
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('pelanggan/status*') ? 'active' : ''}}" href="/pelanggan/status">
            <span data-feather="file-text"></span>
            Status Order
          </a>
        </li>

      </ul>
    </div>
  </nav>
