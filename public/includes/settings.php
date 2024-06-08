<div class="fixed-plugin">
  <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
    <i class="line icon-settings py-2"> </i>
  </a>
  <div class="card shadow-lg ">
    <div class="card-header pb-0 pt-3 ">
      <div class="float-start">
        <h5 class="mt-3 mb-0">
          <h5 class="mt-3 mb-0">Sigven <?php echo date("Y") ?></h5>
        </h5>
        <p>PSUV Amazonas.</p>
      </div>
      <div class="float-end mt-4">
        <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
          <i class="line icon-close"></i>
        </button>
      </div>
      <!-- End Toggle Button -->
    </div>

    <!-- Sidebar Backgrounds -->
    <?php
    if ($_SESSION['nivel'] == '1') {
      $conexiones = '
<div class="card-body pt-sm-3 pt-0"><div class="mt-3">
<h6 class="mb-0">Conexiones</h6>
<p class="text-sm">Ultimas conexiones</p>
</div>
<div>
<ul class="list">';
      $querry222 = "SELECT * FROM log_usuarios ORDER BY id DESC LIMIT 10";
      $search22222 = $conexion->query($querry222);
      if ($search22222->num_rows > 0) {
        while ($resultado2222222 = $search22222->fetch_assoc()) {
          $conexiones .= '<li>' . date("H:i a", $resultado2222222['fecha']) . ' > ' .$resultado2222222['usuario'] . '</li>';
        }
      }

      $conexiones .= '</ul></div>';
      echo $conexiones;
    }
    ?>
    <br>
    <div class="mt-3">
      <h6 class="mb-0">Modo de luz</h6>
      <p class="text-sm">Active o desactive el modo oscuro.</p>
    </div>
    <div class="d-flex">
      <button style="display: none;" class="btn bg-gradient-primary w-100 px-3 mb-2 active" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
      <button style="display: none;" class="btn bg-gradient-primary w-100 px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>

      <a href="../configurar/darkMode.php?id=0" class="btn btn-white mb-0 me-2" style="font-weight: 100;">
        Claro
      </a>
      <a href="../configurar/darkMode.php?id=1" class="btn btn-dark mb-0 me-2" style="font-weight: 100;">
        Oscuro
      </a>
    </div>
    <div>

    <input  style="display: none;" class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
    <br>
      <?php
      if ($_SESSION['respaldos'] == '1' || $_SESSION['nivel'] == '1') {
        echo '<div class="mt-3">
<h6 class="mb-0">Respaldo</h6>
<p class="text-sm">Respaldo de la base de datos en SQL.</p>
</div>

<div class="w-100 text-center">
<a class="btn bg-dark w-100" style="font-weight: 100; color: white" href="../configurar/respaldo.php">Respaldar BD</a>  
</div>
</div>
';
      }
      ?>
    </div>
  </div>