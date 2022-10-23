<!-- Modal -->
<style>
    .form-row-compact .form-group {
        margin-bottom: 4px;
    }
</style>
<div class="modal fade" id="modalFormPaciente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPaciente" action="javascript:void(0);"  name="formPaciente" class="form-horizontal" autocomplete="off">
                    <p class="text-primary" style="margin-bottom: 4px;">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
                    <div class="form-row form-row-compact">
                        <div class="form-group col-md-8">
                            <label for="txtPersona">Paciente<span class="required">*</span></label>
                            <input type="text" class="form-control txtPersona" id="txtPersona" placeholder="nombre, apellido o DPI" name="txtPersona" >
                            <input type="hidden" id="selectedPerson" value="0" name="selectedPerson">
                            <!-- Visor de resutlados de busqueda en tiempo real. -->
                            <div id="searchResultsView" class="form-control" style="height: auto; position: absolute; z-index: 99; border-color: black; display: none;">
                                <table id="resultsTable" style="width: 100%;">
                                    <thead>
                                        <th>Identificación</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                    </thead>
                                    <tbody id="resultsTableBody">
                                    </tbody>
                                </table>
                                <style>
                                    .resultViewelement:hover {
                                        background-color: lightgray;
                                        cursor: pointer;
                                    }
                                </style>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <button id="cleanPerson" style="display: none; position: absolute; bottom: 0px;" class="btn btn-danger" type="button"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cambiar paciente</button>
                        </div>
                    </div>
                    <div class="form-row form-row-compact">
                        <div class="form-group col-md-4">
                            <label for="selectServicio">Servicio<span class="required">*</span></label>
                            <select id="selectServicio" name="selectServicio" class="form-control form-select" aria-label="Selecciona un servicio">
                                <option selected>Selecciona un servicio</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="selectEstado">Estado<span class="required">*</span></label>
                            <select id="selectEstado" name="selectEstado" class="form-control form-select" aria-label="Selecciona un estado" disabled>
                                <option value="1" selected>Operado</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="datePicker">Fecha<span class="required">*</span></label>
                            <input type="date" name="datePicker" id="datePicker" class="form-control" id="txtPersona"  disabled>
                        </div>
                    </div>
                    <hr>
                    <!-- Tabla de insumos, permite agregar, modificar cantidades, etc -->
                    <div class="form-row form-row-compact">
                        <div class="form-group col-md-12">
                            <label for="txtSrchInsumo">Agregar insumo<span class="required">*</span></label>
                            <input type="text" class="form-control txtSrchInsumo" id="txtSrchInsumo" placeholder="Busar insumo" name="txtSrchInsumo" >
                            <div id="searchResultsViewMed" class="form-control" style="height: auto; position: absolute; z-index: 99; border-color: black; display: none;">
                                <table id="resultsTableMed" style="width: 100%;">
                                    <thead>
                                        <th>Codigo</th>
                                        <th>Categoria</th>
                                        <th>Nombre del insumo</th>
                                    </thead>
                                    <tbody id="resultsTableBodyMed">
                                    </tbody>
                                </table>
                                <style>
                                    .resultViewelementMed:hover {
                                        background-color: lightgray;
                                        cursor: pointer;
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                    <div style="height: 238px; overflow-y: scroll; background: lightgrey;">
                        <table class="table table-sm" style="background: white;">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 75px;"># codigo</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Insumo</th>
                                    <th scope="col" style="width: 100px;">Cantidad</th>
                                    <th scope="col" style="width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody id="pedidoContent">
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="tile-footer">
                        <button id="btnSavePedido" class="btn btn-primary" ><i class="fa fa-fw fa-lg fa-check-circle"></i><span>Guardar</span></button>
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewPaciente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos del paciente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Identificación (DPI):</td>
                            <td id="celIdentificacion">654654654</td>
                        </tr>
                        <tr>
                            <td>Nombres:</td>
                            <td id="celNombre">Jacob</td>
                        </tr>
                        <tr>
                            <td>Apellidos:</td>
                            <td id="celApellido">Jacob</td>
                        </tr>
                        <tr>
                            <td>Edad:</td>
                            <td id="celEdad">Larry</td>
                        </tr>
                        <tr>
                            <td>Teléfono:</td>
                            <td id="celTelefono">Larry</td>
                        </tr>
                        <tr>
                            <td>Fecha registro:</td>
                            <td id="celFechaRegistro">Larry</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>