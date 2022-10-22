<!-- Modal -->
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
                <form id="formPaciente" name="formPaciente" class="form-horizontal">
                    <input type="hidden" id="idUsuario" name="idUsuario" value="">
                    <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="txtIdentificacion">Paciente<span class="required">*</span></label>
                            <input type="text" class="form-control txtPersona" id="txtPersona" placeholder="nombre, apellido o DPI" name="txtIdentificacion" required="">
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
                                        <tr>
                                            <td>Alfreds Futterkiste</td>
                                            <td>Maria Anders</td>
                                            <td>Germany</td>
                                        </tr>
                                        <tr>
                                            <td>Centro comercial Moctezuma</td>
                                            <td>Francisco Chang</td>
                                            <td>Mexico</td>
                                        </tr>
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
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="txtIdentificacion">Servicio<span class="required">*</span></label>
                            <select id="selectServicio" name="selectServicio" class="form-control form-select" aria-label="Selecciona un servicio">
                                <option selected>Selecciona un servicio</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="txtIdentificacion">Identificación (DPI) <span class="required">*</span></label>
                            <input type="text" class="form-control" id="txtIdentificacion" name="txtIdentificacion" required="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txtNombre">Nombres <span class="required">*</span></label>
                            <input type="text" class="form-control valid validText" id="txtNombre" name="txtNombre" required="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txtApellido">Apellidos <span class="required">*</span></label>
                            <input type="text" class="form-control valid validText" id="txtApellido" name="txtApellido" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="txtEdad">Edad <span class="required">*</span></label>
                            <input type="text" class="form-control valid validNumber" id="txtEdad" name="txtEdad" required="" onkeypress="return controlTag(event);">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txtTelefono">Teléfono <span class="required">*</span></label>
                            <input type="text" class="form-control valid validNumber" id="txtTelefono" name="txtTelefono" required="" onkeypress="return controlTag(event);">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="txtDireccion">Dirección <span class="required">*</span></label>
                        <input type="text" class="form-control" id="txtDireccion" name="txtDireccion" required="">
                    </div>
                    <hr>
                    <p class="text-primary">Datos Del Responsable.</p>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nombre del Responsable <span class="required">*</span></label>
                            <input class="form-control valid validText" type="text" id="txtResponsable" name="txtResponsable" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Telefono Responsable <span class="required">*</span></label>
                            <input class="form-control valid validNumber" type="text" id="txtTelefonoResp" name="txtTelefonoResp" required="" onkeypress="return controlTag(event);">
                        </div>

                    </div>
                    <div class="form-row">

                    </div>
                    <div class="tile-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
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