console.log("FILE DE PEDIDOS");
function openModal() {
    clearSelectedPerson();
    clearInsumosinput();
    clearPedidosTable();
    rowTable = "";
    document.querySelector('#idUsuario').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector("#formPaciente").reset();
    $('#modalFormPaciente').modal('show');
    //fecha de hoy.
    document.getElementById('datePicker').valueAsDate = new Date();
    document.getElementById('datePicker').min = new Date().toISOString().split("T")[0];
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
        '    <th class="insRowId" scope="row">'+insumo_id+'</th>' +
        '    <td >'+insumo_cat+'</td>' +
        '    <td>'+insumo_nom+'</td>' +
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

function clearPedidosTable(){
    $('#pedidoContent').empty();
}