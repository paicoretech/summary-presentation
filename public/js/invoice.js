// var rows = document.getElementById('myTableId').getElementsByTagName('tbody')[0]
//     .getElementsByTagName('tr').length;

// console.log(rows);

$(document).ready(function() {
    //obtenemos el valor de los input

    $("#btnAdd").click(function() {
        var description = document.getElementById("description").value;
        var precio = document.getElementById("precio").value;
        var i = 1; //contador para asignar id al boton que borrara la fila
        //var fila = '<tr id="row' + i + '"><td>' + description + '</td><td>' + N/A + '</td><td>' + precio + '</td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">Quitar</button></td></tr>'; //esto seria lo que contendria la fila
        if (description.length > 0 && precio.length > 0) {
            var fila =
                '<tr id="row' +
                i +
                '"><td class="text-left">' +
                description +
                '</td><td class="unit">N/A </td><td class="qty">' +
                precio +
                "</td></tr>";
        } else {
            toastr.warning(
                "los campos descripcion y precio no pueden estar vacios."
            );
        }
        i++;

        $("#tbodyI tr:last").after(fila);
        //$("#btnAdd").text(""); //esta instruccion limpia el div adicioandos para que no se vayan acumulando
        var nFilas = $("#mytable tr").length;
        $("#adicionados").append(nFilas - 1);
        //le resto 1 para no contar la fila del header
        document.getElementById("precio").value = "";
        document.getElementById("description").value = "";
        document.getElementById("tbodyI").focus();
    });
    $(document).on("click", ".btn_remove", function() {
        var button_id = $(this).attr("id");
        //cuando da click obtenemos el id del boton
        $("#row" + button_id + "").remove(); //borra la fila
        //limpia el para que vuelva a contar las filas de la tabla
        $("#adicionados").text("");
        var nFilas = $("#mytable tr").length;
        $("#adicionados").append(nFilas - 1);
    });
});
