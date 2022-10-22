console.log("FILE DE PEDIDOS");
function openModal() {
    rowTable = "";
    document.querySelector('#idUsuario').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector("#formPaciente").reset();
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

$(document).on('keyup', '#txtPersona', function () {
    let search_value = this.value.trim();
    console.log('buscando: ', search_value);
    if (search_value.length > 1 && !this.disabled) {
        $('#resultsTableBody').empty();
        $.get(base_url + "/Pacientes/searchUserByMultipleFields/" + search_value, function (data) {
            try {
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
            } catch (error) {
                console.log(error);
            }
        });
        $('#searchResultsView').show();
    } else {
        $('#searchResultsView').hide();
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

$('#cleanPerson').on('click', function () {
    document.getElementById('selectedPerson').value = 0;
    document.getElementById('txtPersona').value = '';
    document.getElementById('txtPersona').disabled = false;
    $('#cleanPerson').hide();

});