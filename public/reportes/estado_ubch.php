<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGVEN</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

</body>

</html>


<?php

$centro = $_GET['c'];

?>

<script>
    function swal() {
        Swal.fire({
            title: 'Ingrese su contraseña',
            input: 'password',
            inputPlaceholder: 'Contraseña...',
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('La contraseña es obligatoria');
                    return false;
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const password = result.value;
                getdata(password); // Llama a tu función con la contraseña
            }
        });


    }
    swal()


    const centro = <?php echo json_encode($centro); ?>;

    function getdata(pass) {
        fetch('estado_ubch_contenido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    pass: pass,
                    c: centro
                })
            })
            .then(response => response.json()) // ✅ convierte el body en JSON
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.status === 'success') {
                    document.write(data.html); // ✅ ahora sí puedes usar data.html
                } else {
                    swal()
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function imprimirContenido() {
        window.print();
    }
</script>