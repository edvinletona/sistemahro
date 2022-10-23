console.log("FILE DE PEDIDOS");
function openModal() {
    clearSelectedPerson();
    clearInsumosinput();
    clearPedidosTable();
    $('#selectServicio').prop('selectedIndex', 0);
    //fecha de hoy.
    document.getElementById('datePicker').valueAsDate = new Date();
    document.getElementById('datePicker').min = new Date().toISOString().split("T")[0];
    rowTable = "";
    $('#modalFormPaciente').modal('show');

}

console.log("consultando los servicios");
$.get(base_url + "/Servicios/getServicesList", function (data) {
    try {
        console.log(data);
        let results = JSON.parse(data);
        if (results.data && results.data.length > 0) {
            console.log("Recorriendo array")
            for (const element of results.data) {
                console.log("Agregando ", element);
                $('#selectServicio').append('<option value="' + element.idservicio + '">' + element.nombreservicio + '</option>');
            }
        } else {
            $('#resultsTableBody').append('<tr><td colspan="3"><b>sin coincidencias.</b><td></tr>');
        }
    } catch (error) {
        console.log(error);
    }
});


//omite busquedas viejas
let search_count = 0;
/**
 * Busqueda de pacientes en tiempo real, pude ser identificacion , nombre o apellido.
 */
$(document).on('keyup', '#txtPersona', function (e) {
    console.log(e.key);
    search_count++;
    let current_search = search_count;
    let alpha_numeric_regex = /[a-z|A-Z|0-9]+/;
    //solo procesamos alfa numericos, lo demas puede ocacionar falsas busquedas. 
    if (e.key.length == 1 && e.key.match(alpha_numeric_regex)) {
        let search_value = this.value.trim();
        console.log('buscando: ', search_value);
        //Minimo de dos letras para poder hacer una busqueda
        if (search_value.length > 1 && !this.disabled) {
            $.get(base_url + "/Pacientes/searchUserByMultipleFields/" + search_value, function (data) {
                if (current_search == search_count) {
                    try {
                        $('#resultsTableBody').empty();
                        let results = JSON.parse(data);
                        console.log(results);
                        if (results.data && results.data.length > 0) {
                            console.log("Recorriendo array")
                            for (const element of results.data) {
                                console.log("Agregando ", element);
                                $('#resultsTableBody').append('<tr class="resultViewelement" id="perElmnt-' + element.idpersona + '"><td class="rstlvwid">' + element.identificacion + '</td><td class="rstlvwname">' + element.nombres + '</td> <td class="rstlvwlast">' + element.apellidos + '</td></tr>');
                            }
                        } else {
                            $('#resultsTableBody').append('<tr><td colspan="3"><b>sin coincidencias.</b><td></tr>');
                        }
                        $('#searchResultsView').show();
                    } catch (error) {
                        console.log(error);
                        $('#resultsTableBody').append('<tr><td colspan="3"><b>Error en la busqueda.</b><td></tr>');
                    }
                }
            });
        } else {
            $('#searchResultsView').hide();
        }
    }

});

/**
 * Cuando el usuario seleciona una persona de la lista de resultados de la busquedas.
 */
$(document).on('click', '.resultViewelement', function () {
    let id_persona = this.id.replace('perElmnt-', '');
    let register_display = this.querySelector('.rstlvwid').innerHTML + ' | ' + this.querySelector('.rstlvwname').innerHTML + ' | ' + this.querySelector('.rstlvwlast').innerHTML;
    document.getElementById('txtPersona').disabled = true;
    document.getElementById('txtPersona').value = register_display;
    document.getElementById('selectedPerson').value = id_persona;
    $('#searchResultsView').hide();
    $('#cleanPerson').show();

});


function clearSelectedPerson() {
    document.getElementById('selectedPerson').value = 0;
    document.getElementById('txtPersona').value = '';
    document.getElementById('txtPersona').disabled = false;
    $('#cleanPerson').hide();
    $('#searchResultsView').hide();
}

$('#cleanPerson').on('click', clearSelectedPerson);


let global_insumo_search_count = 0;
/**
 * Busqueda de insumos en tiempo real unicamente por nombre.
 */
$(document).on('keyup', '#txtSrchInsumo', function (e) {
    global_insumo_search_count++;
    let insumo_search_count = global_insumo_search_count;
    let alpha_numeric_regex = /[a-z|A-Z|0-9]+/;
    //solo procesamos alfa numericos, lo demas puede ocacionar falsas busquedas. 
    if (e.key.length == 1 && e.key.match(alpha_numeric_regex)) {
        let search_value = this.value.trim();
        console.log('buscando insumo: ', search_value);
        //Minimo de dos letras para poder hacer una busqueda
        if (search_value.length > 1 && !this.disabled) {
            $.get(base_url + "/Insumos/serachInsumoByName/" + search_value, function (data) {
                if (insumo_search_count == global_insumo_search_count) {
                    try {
                        $('#resultsTableBodyMed').empty();
                        let results = JSON.parse(data);
                        console.log(results);
                        if (results.data && results.data.length > 0) {
                            console.log("Recorriendo array")
                            for (const element of results.data) {
                                console.log("Agregando ", element);
                                $('#resultsTableBodyMed').append('<tr class="resultViewelementMed" id="insElmnt-' + element.idinsumos + '"><td class="rstlvwid">' + element.idinsumos + '</td><td class="rstlvwcat">' + element.nombrecat + '</td><td class="rstlvwname">' + element.nombre + '</td></tr>');
                            }
                        } else {
                            $('#resultsTableBodyMed').append('<tr><td colspan="2"><b>sin coincidencias.</b><td></tr>');
                        }
                        $('#searchResultsViewMed').show();
                    } catch (error) {
                        console.log(error);
                        $('#resultsTableBodyMed').append('<tr><td colspan="2"><b>Error en la busqueda.</b><td></tr>');
                    }
                }
            });
        } else {
            $('#searchResultsViewMed').hide();
        }
    }

});

function clearInsumosinput() {
    document.getElementById('txtSrchInsumo').value = '';
    $('#resultsTableBodyMed').empty();
    $('#searchResultsViewMed').hide();
}

/**
 * Click sobre un resutlado de insumos
 */
$(document).on('click', '.resultViewelementMed', function () {
    let insumo_id = this.id.replace('insElmnt-', '');
    let insumo_cat = this.querySelector('.rstlvwcat').innerHTML;
    let insumo_nom = this.querySelector('.rstlvwname').innerHTML;
    $('#pedidoContent').append(
        '<tr>' +
        '    <th class="insRowId" insumo-id="' + insumo_id + '" scope="row">' + insumo_id + '</th>' +
        '    <td >' + insumo_cat + '</td>' +
        '    <td>' + insumo_nom + '</td>' +
        '    <td class="insRowCant">' +
        '        <input class="form-control form-control-sm" type="number" min="1" value="1">' +
        '    </td>' +
        '    <th>' +
        '        <button class="btn btn-sm btn-danger" type="button"><i class="fa fa-fw fa-lg fa-times"></i>Eliminar</button>' +
        '    </th>' +
        '</tr > '
    );
    clearInsumosinput();
});

function clearPedidosTable() {
    $('#pedidoContent').empty();
}

/**
 * Crea el pedido en base de datos
 */
$('#btnSavePedido').on('click', function () {
    let pedido_persona = $('#selectedPerson').val();
    if (pedido_persona == '0') {
        $('#txtPersona').prop('required', true);
        document.getElementById('txtPersona').setCustomValidity('Ingresar paciente.');
        $('#txtPersona').checkValidity();
        $('#txtPersona').prop('required', false);
        return;
    }
    let pedido_servicio = $('#selectServicio').val();
    if (pedido_servicio == 'Selecciona un servicio') {
        $('#selectServicio').prop('required', true);
        document.getElementById('selectServicio').setCustomValidity('Selecione un servicio.');
        $('#selectServicio').checkValidity();
        $('#selectServicio').prop('required', false);
        return;
    }
    let pedido = {};
    pedido.status = 1; //Operado por default
    pedido.personaid = pedido_persona;
    pedido.servicioid = pedido_servicio;
    //Ahora sacamos el listado de insumos.
    let pedido_insumos = [];
    let tabla_pedido = document.getElementById('pedidoContent');
    let filas = tabla_pedido.querySelectorAll('tr');
    if (filas.length == 0) {
        //TODO indicar que agregue insumos (de forma bonita)
        alert("Debe de ingresar un insumo para continuar");
        return;
    }
    for (const row_element of filas) {
        let new_insumo = {
            idinsumo: row_element.querySelector('.insRowId').getAttribute('insumo-id'),
            cantidad: row_element.querySelector('.insRowCant input').value
        }
        pedido_insumos.push(new_insumo);
    }
    pedido.insumos = JSON.stringify(pedido_insumos);
    $.post(base_url + '/Pedidos/setPedido', pedido).done(function (data) {
        try {
            data = JSON.parse(data);
            console.log(data);
            if (data.status) {
                getPedidosList();
                $('#modalFormPaciente').modal('toggle');
            } else {
                //TODO controlar error de que no se inserto
            }
        } catch (error) {
            console.log(error, data);
        }
    });
});

/**
 * Obtener los pedidios para mostrar en pantalla.
 */
function getPedidosList() {
    $('#tableListPedidos').empty();
    $.get(base_url + "/Pedidos/getPedidosList", function (data) {
        try {
            console.log(data);
            let results = JSON.parse(data);
            if (results.data && results.data.length > 0) {
                console.log("Recorriendo array")
                for (const element of results.data) {
                    $('#tableListPedidos').append(
                        '<tr id="pedElm-' + element.idpedido + '" >' +
                        '<td>' + element.idpedido + '</td>' +
                        '<td>' + element.nombres + '</td>' +
                        '<td>' + element.apellidos + '</td>' +
                        '<td>' + element.nombreservicio + '</td>' +
                        '<td>' + element.fecha + '</td>' +
                        '<td>' +
                        '<button pedido="' + element.idpedido + '" class="btn btn-sm btn-primary btnViewPedido" type="button"><i class="fa fa-fw fa-lg fa-eye"></i>Ver</button>' +
                        '</td>' +
                        '</tr>'
                    );

                    console.log("Agregando ", element);
                    $('#selectServicio').append('<option value="' + element.idservicio + '">' + element.nombreservicio + '</option>');
                }
            } else {
                $('#tableListPedidos').append('<tr><td colspan="6"><b>Sin pedidos.</b><td></tr>');
            }
        } catch (error) {
            console.log(error);
        }
    });
}

getPedidosList();

/**
 * Ver un pedido
 */
$(document).on('click', '.btnViewPedido', function () {
    $('#tblPedidoPersonaviews').empty();
    $('#tblPedidoviews').empty();
    $('#pedidoViewId').empty();
    $('#pedidoViewServ').empty();
    $('#pedidoViewFech').empty();
    $.get(base_url + "/Pedidos/getPedido/" + this.getAttribute('pedido'), function (data) {
        try {

            let results = JSON.parse(data);
            if (results.data) {
                results = results.data;
                console.log(results);
                //datos del pedido
                $('#pedidoViewId').text(results.pedido.idpedido);
                $('#pedidoViewServ').text(results.pedido.nombreservicio);
                $('#pedidoViewFech').text(results.pedido.fecha);
                //Agregamos datos de la persona
                $('#tblPedidoPersonaviews').append(
                    '<tr >' +
                    '<td>' + results.pedido.identificacion + '</td>' +
                    '<td>' + results.pedido.nombres + '</td>' +
                    '<td>' + results.pedido.apellidos + '</td>' +
                    '</tr>'
                );
                //Detalle del pedido
                for (const element of results.detalle) {
                    $('#tblPedidoviews').append(
                        '<tr>' +
                        '<td>' + element.idinsumos + '</td>' +
                        '<td>' + element.nombrecat + '</td>' +
                        '<td>' + element.nombre + '</td>' +
                        '<td>' + element.cantidad + '</td>' +
                        '</tr>'
                    );
                }
            } else {
                $('#tableListPedidos').append('<tr><td colspan="6"><b>Sin pedidos.</b><td></tr>');
            }
        } catch (error) {
            console.log(error);
        }
    });
    $('#modalViewPedido').modal('toggle');
});