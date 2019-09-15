<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <h3 align="center">Cuarteles</h3> 
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <div class="trans">
                    
                        <button type="button" @click="abrirModal('cuartel','registrar')" class="btn btn-primary">
                            <i class="icon-plus"></i> Ingresar nuevo Cuartel
                        </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarcuartel(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarcuartel(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo de uva</th>
                                    <th>Tipo de riego</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cuartel in arraycuartel" :key="cuartel.id">
                                    <td>
                                        <button type="button" @click="abrirModal('cuartel','actualizar',cuartel)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="cuartel.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarcuartel(cuartel.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarcuartel(cuartel.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="cuartel.nombreCuartel"></td>
                                    <td v-text="cuartel.Tipo_uva"></td>
                                    <td v-text="cuartel.Tipo_riego"></td>
                                    <td>
                                        <div v-if="cuartel.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de su cuartel">
                                        
                                    </div>
                                </div>
                              

                                <div class="form-group row">
                                <label class="col-md-3 form-control-label">Tipo de uva:</label>
                        
                        
                            <div class="col-md-9">
                            <select v-model="uva" class="form-control"   >
                             <option value="Sauvignon blanc">Sauvignon blanc</option> 
                              <option value="Chardonnay" >Chardonnay</option>
                              <option value="Riesling">Riesling</option>
                              <option value="Cabernet Sauvignon">Cabernet Sauvignon</option>
                              <option value="Merlot">Merlot</option>
                              <option value="Carménère">Carménère</option>
                              <option value="Syrah">Syrah</option>
                              <option value="Pinot Noir">Pinot Noir</option>
                              </select>
                            
                        </div>
                    </div>

                                 <div class="form-group row">
                                <label class="col-md-3 form-control-label">Tipo de riego:</label>
                        
                        
                            <div class="col-md-9">
                            <select v-model="riego" class="form-control"   >
                             <option value="Manual">Riego manual</option> 
                              <option value="Goteo" >Riego por goteo</option>
                              
                              </select>
                            
                        </div>
                    </div>
                                <div v-show="errorcuartel" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjcuartel" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarcuartel()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarcuartel()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                cuartel_id: 0,
                nombre : '',
                uva : '',
                riego: '',
                arraycuartel : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorcuartel : 0,
                errorMostrarMsjcuartel : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarcuartel (page,buscar,criterio){
                let me=this;
                var url= '/cuartel?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraycuartel = respuesta.cuarteles.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarcuartel(page,buscar,criterio);
            },
            registrarcuartel(){
                if (this.validarcuartel()){
                    return;
                }
                
                let me = this;

                axios.post('/cuartel/registrar',{
                    'nombre': this.nombre,
                    'uva': this.uva,
                    'riego':this.riego
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarcuartel(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarcuartel(){
               if (this.validarcuartel()){
                    return;
                }
                
                let me = this;

                axios.put('/cuartel/actualizar',{
                   'nombre': this.nombre,
                    'uva': this.uva,
                    'riego':this.riego,
                    'id': this.cuartel_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarcuartel(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarcuartel(id){
               swal({
                title: 'Esta seguro de desactivar este cuartel?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/cuartel/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarcuartel(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El cuartel ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarcuartel(id){
               swal({
                title: 'Esta seguro de activar este cuartel?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/cuartel/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarcuartel(1,'','nombre');
                        swal(
                        'Activado!',
                        'El cuartel ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            validarcuartel(){
                this.errorcuartel=0;
                this.errorMostrarMsjcuartel =[];

                if (!this.nombre) this.errorMostrarMsjcuartel.push("El nombre del cuartel no puede estar vacío.");
                if (!this.uva) this.errorMostrarMsjcuartel.push("Debe seleccionar un tipo de uva.");
                if (!this.riego) this.errorMostrarMsjcuartel.push("Debe seleccionar el tipo de riego");
                if (this.errorMostrarMsjcuartel.length) this.errorcuartel = 1;

                return this.errorcuartel;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "cuartel":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar cuartel';
                                this.nombre= '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar cuartel';
                                this.tipoAccion=2;
                                this.cuartel_id=data['id'];
                                this.nombre = data['nombre'];
                                this.descripcion= data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarcuartel(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .trans{
  width: 1000px;
  
  display: flex;
  justify-content: center;
}
</style>