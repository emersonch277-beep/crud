<?php

require_once "conexion.php";

/* REGISTRAR */
if (isset($_POST['registrar'])) {

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];

    $sql = "INSERT INTO estudiantes
            (nombre, apellido, correo)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $apellido, $correo);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* ELIMINAR */
if (isset($_GET['eliminar'])) {

    $id = $_GET['eliminar'];

    $sql = "DELETE FROM estudiantes WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* EDITAR */
if (isset($_POST['editar'])) {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];

    $sql = "UPDATE estudiantes
            SET nombre=?, apellido=?, correo=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssi",
        $nombre,
        $apellido,
        $correo,
        $id
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* DATOS PARA EDITAR */
$editar = null;

if (isset($_GET['editar'])) {

    $id = $_GET['editar'];

    $sql = "SELECT * FROM estudiantes WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado_editar = $stmt->get_result();

    $editar = $resultado_editar->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Sistema de Registro</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    margin: 40px;
}

.contenedor {
    max-width: 900px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
}

h1 {
    text-align: center;
}

input {
    padding: 10px;
    margin: 5px;
    width: 25%;
}

button {
    padding: 10px 20px;
    background: #333;
    color: white;
    border: none;
    cursor: pointer;
}

table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}

th {
    background: #333;
    color: white;
}

a {
    text-decoration: none;
    margin: 5px;
}

.editar {
    color: blue;
}

.eliminar {
    color: red;
}

</style>

</head>

<body>

<div class="contenedor">

<h1>Sistema de Registro de Estudiantes</h1>

<?php if ($editar): ?>

<h2>Editar estudiante</h2>

<form method="POST">

<input type="hidden"
       name="id"
       value="<?php echo $editar['id']; ?>">

<input type="text"
       name="nombre"
       value="<?php echo $editar['nombre']; ?>"
       required>

<input type="text"
       name="apellido"
       value="<?php echo $editar['apellido']; ?>"
       required>

<input type="email"
       name="correo"
       value="<?php echo $editar['correo']; ?>"
       required>

<button type="submit" name="editar">
Actualizar
</button>

<a href="index.php">Cancelar</a>

</form>

<?php else: ?>

<h2>Registrar estudiante</h2>

<form method="POST">

<input type="text"
       name="nombre"
       placeholder="Nombre"
       required>

<input type="text"
       name="apellido"
       placeholder="Apellido"
       required>

<input type="email"
       name="correo"
       placeholder="Correo"
       required>

<button type="submit" name="registrar">
Registrar
</button>

</form>

<?php endif; ?>


<h2>Lista de estudiantes</h2>

<table>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Apellido</th>
<th>Correo</th>
<th>Acciones</th>

</tr>

<?php

$resultado = $conn->query(
    "SELECT * FROM estudiantes ORDER BY id DESC"
);

while ($fila = $resultado->fetch_assoc()):

?>

<tr>

<td>
<?php echo $fila['id']; ?>
</td>

<td>
<?php echo htmlspecialchars($fila['nombre']); ?>
</td>

<td>
<?php echo htmlspecialchars($fila['apellido']); ?>
</td>

<td>
<?php echo htmlspecialchars($fila['correo']); ?>
</td>

<td>

<a class="editar"
   href="index.php?editar=<?php echo $fila['id']; ?>">
Editar
</a>

<a class="eliminar"
   href="index.php?eliminar=<?php echo $fila['id']; ?>"
   onclick="return confirm('¿Eliminar este registro?');">
Eliminar
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</body>
</html>
