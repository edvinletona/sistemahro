<?php 
    headerAdmin($data); 
    //getModal('modalInsumos',$data);
?>
  <main class="app-content">    
      <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> <?= $data['page_title'] ?>
                <?php if($_SESSION['permisosMod']['w']){ ?>
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Nuevo</button>
              <?php } ?>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-clipboard fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/pedidos"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tablePedidos">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Nombres</th>   
                          <th>Apellidos</th>                                                 
                          <th>Servicio</th>
                          <th>Fecha</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </main>
<?php footerAdmin($data); ?>