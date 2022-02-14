    <li class="nav-item menu-open">
      <p>
    <li class="nav-item">
      <a href="" class="nav-link active">
        <i class="far fa-circle nav-icon"></i>
        <p>Dashboard</p>
      </a>
    </li>
    </a>
    </li>
    <?php
    if ($this->session->userdata('status') == 'loggedin') { ?>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-chart-pie"></i>
          <p>
            Data Master
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="mendoan" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Mendoan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="kartu" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Kartu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="spam_pelanggan" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Pelanggan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="spam" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data SPAM</p>
            </a>
          </li>
        </ul>
      </li>
      <!-- <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> -->

      <li class="nav-item">
        <a href="#" class="nav-link" onclick="loaddaftarspam()">
          <i class="nav-icon fas fa-chart-pie"></i>
          <p>
            Data Logger
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="logger" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Logger</p>
            </a>
          </li>
          <div id="list-menu-spam">

          </div>

        </ul>
      </li>
    <?php } ?>