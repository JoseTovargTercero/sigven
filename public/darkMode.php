<?php 
$idUser = $_SESSION['id'];
$query = "SELECT * FROM usuarios WHERE id='$idUser'";
$search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($row = $search->fetch_assoc()) {
            $darkMode = $row['darkMode'];
    }
}

if ( $darkMode == 1) {
?>

<style>
  .bg-gray-100 {
    background-color: #1e1e1e !important;
}

.card-header {
    background-color: #38393c;
}

.card {
    background-color: #38393c;
}

.form-control {
    background-color: #46474b;
}

.input-group-text {
    background-color: #46474b;
}

a{
  color: #d0d3d7 !important;
}
h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
    color: #d0d3d7 !important;
}
.text-dark {
    color: #d0d3d7 !important;
}

.fixed-plugin .fixed-plugin-button {
    background: #38393c;
}

.table {
    color: #d0d3d7;
    border-color: #484848;
}
table {
    color: #d0d3d7;
    border-color: #484848;
}


.navbar-vertical .navbar-nav>.nav-item .nav-link.active {
    background-color: #46474b;
}

.btn-white {
    color: #000 !important;
}

.text-body {
    color: #d0d3d7 !important;
}

.form-control {
  background-color: #46474b !important;
  color:  #d0d3d7;
  border: 1px solid #303030;
}
.form-control:focus {
  background-color: #46474b !important;
  color:  #d0d3d7;
}

.swal2-popup {
    background: #121212 !important;
}

.swal2-styled.swal2-confirm {
    background-color: #2a2b2d !important;
}

.blur {
    background-color: rgb(56 57 60) !important;
}

.shadow-blur {
    box-shadow: none !important;
}
.loader {
    background-color: #1e1e1e !important;
}
.blue {
    color: #4b8181 !important;
}
.dropdown-menu {
    background-color: #323234;
}
.mb-2 :hover {
    background-color: #1e1e1e;
}
.divisor{
    background-color: #4e4e4e !important;
  }
  .text-muted {
  color: #46474b !important;
}
.text-sm {
    color: #cbcbcb !important;
}
.text-xs {
    color: #9f9fa0 !important;
}

.timeline:before {
    border-right: 2px solid #3a3a3a;
}

.timeline-step {
    background: #3a3a3a;
}


.activo {
    color: #344767;
    background-color: #46474b;
  }
.back:hover {
    color: #344767;
    background-color: #46474b;
}

</style>



<?php } ?>