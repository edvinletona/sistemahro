let tableInsumos;
let rowTable = "";

$(document).on('focusin',function(e){
    if ($(e.target).closest(".tox-dialog").length){
        e.stopImmediatePropagation();
    }
});

tableInsumos = $('#tableInsumos').dataTable( {
    "aProcessing":true,
    "aServerSide":true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Insumos/getInsumos",
        "dataSrc":""
    },
    "columns":[
        {"data":"idinsumos"},
        {"data":"nombre"},
        {"data":"descripcion"},            
        {"data":"nombrecat"},
        {"data":"status"},
        {"data":"options"}
    ],
    'dom': 'lBfrtip',
    'buttons': [
        {
            "extend": "copyHtml5",
            "text": "<i class='far fa-copy'></i> Copiar",
            "titleAttr":"Copiar",
            "className": "btn btn-secondary"
        },{
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Excel",
            "titleAttr":"Esportar a Excel",
            "className": "btn btn-success"
        },{
            "extend": "pdfHtml5",
            "text": "<i class='fas fa-file-pdf'></i> PDF",
            "titleAttr":"Esportar a PDF",
            "className": "btn btn-danger"
        },{
            "extend": "csvHtml5",
            "text": "<i class='fas fa-file-csv'></i> CSV",
            "titleAttr":"Esportar a CSV",
            "className": "btn btn-info"
        }
    ],
    "resonsieve":"true",
    "bDestroy": true,
    "iDisplayLength": 10,
    "order":[[0,"desc"]]      


});


window.addEventListener('load', function() {



    //nuevo insumo
    if(document.querySelector("#formInsumo")){
        let formInsumo = document.querySelector("#formInsumo");
        formInsumo.onsubmit = function(e) {
            e.preventDefault();            
            let strNombre = document.querySelector('#txtNombre').value; 
            let intStatus = document.querySelector('#listStatus').value;
                

            if( strNombre == '')
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }
            divLoading.style.display = "flex";
            tinyMCE.triggerSave(); //hace el llamado del contenido del editor 

            let request = (window.XMLHttpRequest)  ?
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Insumos/setInsumo'; 
            let formData = new FormData(formInsumo);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){                   
                    let objData = JSON.parse(request.responseText);                  
                    if(objData.status)
                    {
                        swal("", objData.msg , "success");
                        document.querySelector("#idInsumo").value = objData.idinsumos;
                        document.querySelector("#containerGallery").classList.remove("notblock");
                        if(rowTable == ""){
                            tableInsumos.api().ajax.reload();
                        }else{
                            htmlStatus = intStatus == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>';
                           
                            rowTable.cells[1].textContent = strNombre;                            
                            rowTable.cells[4].innerHTML =  htmlStatus;
                            rowTable = ""; 
                        }
                       
                    }else{
                            swal("Error", objData.msg,"error");
                        }
                        
                }
                
                divLoading.style.display = "none";
                return false;
            }       
                           
        }
    }




    if(document.querySelector(".btnAddImage")){
        let btnAddImage =  document.querySelector(".btnAddImage");
        btnAddImage.onclick = function(e){
         let key = Date.now();
         let newElement = document.createElement("div");
         newElement.id= "div"+key;
         newElement.innerHTML = `
             <div class="prevImage"></div>
             <input type="file" name="foto" id="img${key}" class="inputUploadfile">
             <label for="img${key}" class="btnUploadfile"><i class="fas fa-upload "></i></label>
             <button class="btnDeleteImage notblock" type="button" onclick="fntDelItem('#div${key}')"><i class="fas fa-trash-alt"></i></button>`;
         document.querySelector("#containerImages").appendChild(newElement);
         document.querySelector("#div"+key+" .btnUploadfile").click();
         fntInputFile();
        }
     
        }
     


     fntInputFile();
    fntCategoriaInsumo();

}, false);


tinymce.init({
	selector: '#txtDescripcion',
	width: "100%",
    height: 400,    
    statubar: true,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});



function fntInputFile(){
    let inputUploadfile = document.querySelectorAll(".inputUploadfile");
    inputUploadfile.forEach(function(inputUploadfile) {
        inputUploadfile.addEventListener('change', function(){
            let idInsumo = document.querySelector("#idInsumo").value;
            let parentId = this.parentNode.getAttribute("id");
            let idFile = this.getAttribute("id");            
            let uploadFoto = document.querySelector("#"+idFile).value;
            let fileimg = document.querySelector("#"+idFile).files;
            let prevImg = document.querySelector("#"+parentId+" .prevImage");
            let nav = window.URL || window.webkitURL;
            if(uploadFoto !=''){
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
                    prevImg.innerHTML = "Archivo no válido";
                    uploadFoto.value = "";
                    return false;
                }else{
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;

                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = base_url+'/Insumos/setImage'; 
                    let formData = new FormData();
                    formData.append('idinsumos',idInsumo);
                    formData.append("foto", this.files[0]);
                    request.open("POST",ajaxUrl,true);
                    request.send(formData);
                    request.onreadystatechange = function(){
                        if(request.readyState != 4) return;
                        if(request.status == 200){
                            let objData = JSON.parse(request.responseText);
                           
                            if(objData.status){
                                prevImg.innerHTML = `<img src="${objeto_url}">`;
                                document.querySelector("#"+parentId+" .btnDeleteImage").setAttribute("imgname",objData.imgname);
                                document.querySelector("#"+parentId+" .btnUploadfile").classList.add("notblock");
                                document.querySelector("#"+parentId+" .btnDeleteImage").classList.remove("notblock");
                            }else{
                                swal("Error", objData.msg , "error");
                            }
                        }
                    }

                }
            }

        });
    });
}

function fntDelItem(element){
    let nameImg = document.querySelector(element+' .btnDeleteImage').getAttribute("imgname");
    let idInsumo = document.querySelector("#idInsumo").value;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Insumos/delFile'; 

    let formData = new FormData();
    formData.append('idinsumos',idInsumo);
    formData.append("file",nameImg);
    request.open("POST",ajaxUrl,true);
    request.send(formData);
    request.onreadystatechange = function(){
        if(request.readyState != 4) return;
        if(request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                let itemRemove = document.querySelector(element);
                itemRemove.parentNode.removeChild(itemRemove);
            }else{
                swal("", objData.msg , "error");
            }
        }
    }
}

function fntViewInfo(idinsumos){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Insumos/getInsumo/'+idinsumos;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                
                let htmlImage = "";
                let objInsumo = objData.data;
                let estadoInsumo = objInsumo.status == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';

               
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celTipoCategoria").innerHTML = objData.data.categoria;
                document.querySelector("#celEstado").innerHTML = estadoInsumo;
                document.querySelector("#celDescripcion").innerHTML = objData.data.descripcion;
                if(objInsumo.images.length > 0){
                    let objInsumos = objInsumo.images;
                    for (let p = 0; p < objInsumos.length; p++) {
                        htmlImage +=`<img src="${objInsumos[p].url_image}"></img>`;
                    }
                }
                document.querySelector("#celFotos").innerHTML = htmlImage;
                $('#modalViewInsumo').modal('show');
               
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}


function fntEditInfo(element,idinsumos){
    rowTable = element.parentNode.parentNode.parentNode; 
    document.querySelector('#titleModal').innerHTML ="Actualizar Insumo";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Insumos/getInsumo/'+idinsumos;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status)
            {
                let htmlImage = "";
                let objInsumo = objData.data;

                document.querySelector("#idInsumo").value = objInsumo.idinsumos;                
                document.querySelector("#txtNombre").value = objInsumo.nombre;
                document.querySelector("#txtDescripcion").value = objInsumo.descripcion;                
                document.querySelector("#listCategoria").value = objInsumo.categoriaid;

                tinyMCE.activeEditor.setContent(objInsumo.descripcion);
                $('#listCategoria').selectpicker('render');
                $('#listRolid').selectpicker('render');

                if(objInsumo.images.length > 0){
                    let objInsumos = objInsumo.images;
                    for (let p = 0; p < objInsumos.length; p++) {
                        let key = Date.now()+p;
                        htmlImage +=`<div id="div${key}">
                            <div class="prevImage">
                            <img src="${objInsumos[p].url_image}"></img>
                            </div>
                            <button type="button" class="btnDeleteImage" onclick="fntDelItem('#div${key}')" imgname="${objInsumos[p].img}">
                            <i class="fas fa-trash-alt"></i></button></div>`;
                    }
                }

                document.querySelector("#containerImages").innerHTML = htmlImage;   
                document.querySelector("#containerGallery").classList.remove("notblock");                                      
                $('#modalFormInsumo').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }              
    }
}

function fntDelInfo(idinsumos){

    swal({
        title: "Eliminar Insumo",
        text: "¿Realmente quiere eliminar el Insumo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) 
        {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Insumos/delInsumo';
            let strData = "idInsumo="+idinsumos;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableInsumos.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}



function fntCategoriaInsumo(){
    if(document.querySelector('#listCategoria')){
        let ajaxUrl = base_url+'/Categorias/getSelectCategorias';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#listCategoria').innerHTML = request.responseText;
                $('#listCategoria').selectpicker('render');
            }
        }
    }
}


function openModal()
{
    rowTable = "";
    document.querySelector('#idInsumo').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Insumo";
    document.querySelector("#formInsumo").reset();
    document.querySelector("#containerGallery").classList.add("notblock");
    document.querySelector("#containerImages").innerHTML = "";
    $('#modalFormInsumo').modal('show');
}

