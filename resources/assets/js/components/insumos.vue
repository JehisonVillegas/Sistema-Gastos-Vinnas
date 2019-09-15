<template>
   <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
               <h3 align="center">Insumos en bodega</h3> 
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        
                        
                        <div class="trans">
                        <button type="button" @click="abrirModal('insumos','registrar')" class="btn btn-primary" >
                            <i class="icon-plus"></i> Ingresar Nuevo Insumo
                        </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="trans">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="detalle">detalle</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarInsumos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarInsumos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered  table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    
                                    <th>Descripción</th>
                                    <th>Categoria</th>
                                    <th>Unidad</th>
                                    <th>Precio unitario</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="insumos in arrayInsumos" :key="insumos.id">
                                    <td>
                                        <button type="button" @click="abrirModal('insumos','actualizar',insumos)" class="btn btn-warning btn-sm" >
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="insumos.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarInsumos(insumos.id)">
                                          <i class="icon-trash"></i>
                                        </button>
                                       </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarInsumos(insumos.id)">
                                          <i class="icon-check"></i>
                                        </button>
                                    </template>
                                    </td>
                                    <td v-text="insumos.nombre"></td>
                                    <td v-text="insumos.detalle"></td>
                                    <td v-text="insumos.nombreCategoriaInsumo"></td>
                                    <td v-text="insumos.unidad_de_medida"></td>
                                    <td v-text="insumos.Precio_Unitario"></td>
                                    <td>

                                        <div v-if="insumos.condicion">
                                        <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                             <span class="badge badge-danger">desactivado</span>
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
                                <li class="page-item " v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'activo': '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page">  </a>
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
            <div class="modal fade"  tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#572364;">
                            <h4 class="modal-title" v-text="tituloModal"> </h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                               <div class="form-group row">
                        <label class="col-md-3 form-control-label">Categoria:</label>
                        
                        
                            <div class="col-md-9">
                                    <select class="form-control" v-model="idCategoria">
                                      <option value="0" disabled>Seleccione Categoria</option>  
                                      <option v-for="categoria in arrayCategorias" :key="categoria.id" :value="categoria.id" v-text="categoria.nombreCategoriaInsumo"></option>
                                    </select>
                                  
                                </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Unidad de medida</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="unidad" class="form-control" placeholder="Nombre de categoría">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="number-input">Precio por Unidad</label>
                                    <div class="col-md-9">
                                        <input type="int" v-model="precioU" class="form-control" placeholder="ingrese el precio por unidad minima">
                                    </div>
                                </div>


                       

                         <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">detalle</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="detalle" class="form-control" placeholder="ingrese un detalle si lo desea">
                                    </div>
                                </div>

                              <div v-show="errorInsumos"  class="form-group row div-error">
                                  <div class="text-center text-error">
                                       <div v-for="error in MostrarErrores" :key="error" v-text="error"></div>

                                      </div>
                                  </div>



                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button"  v-if="tipoAction==1" class="btn btn-primary" @click="registrarInsumos()">Guardar</button>
                            <button type="button"  v-if="tipoAction==2" class="btn btn-primary" @click="actualizarInsumo()">Actualizar</button>
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
         data(){
            return{
                nombre:'',
                cantidad:'',
                unidad:'',
                precioU:'',
                tipo:'',
                detalle:'',
                arrayInsumos : [],
                arrayCategorias:[],
                modal : 0,
                tituloModal : '',
                tipoAction : 0,
                errorInsumos: 0,
                MostrarErrores: [],
                insumo_id:0,
                idCategoria:0,
                pagination:{
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                  offset : 3,
                  criterio :'nombre',
                  buscar :''

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
         methods :{


                selectCategoria(){
            let me=this;
                var url= '/categoriaInsumos/selectCategoriaInsumos';
                axios.get(url).then(function (response) {
                   var respuesta= response.data;
                   me.arrayCategorias=respuesta.categorias;
                 
                   
                })
                .catch(function (error) {
                    console.log(error);
                });

           },

              listarInsumos(page,buscar,criterio){
                  let me=this;
                  var url='/insumos?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                 axios.get(url).then(function (response) {
                     var respuesta= response.data;
                   me.arrayInsumos = respuesta.insumos.data;
                   me.pagination= respuesta.pagination;
                    
                }).catch(function (error) {console.log(error);
                });


              }, 

              actualizarInsumo(){
               if(this.validar()){
                          return;
                      }
                   
                     let me = this;
                    
                    axios.put('/insumos/actualizar',{
                          'nombre': this.nombre,
                          
                          'unidad' : this.unidad,
                          'precioU' : this.precioU,
                          'tipo' : this.idCategoria,
                          'detalle' : this.detalle,
                          'id' : this.insumo_id

                    }).then(function(response){
                        me.cerrarModal();
                        me.listarInsumos(1,'','nombre');
                    }).catch(function(error){
                        console.log(error);
                    })

              },
              registrarInsumos(){
                      if(this.validar()){
                          return;
                      }
                   
                     let me = this;
                    
                    axios.post('/insumos/ingresar',{
                          'nombre': this.nombre,
                          
                          'unidad' : this.unidad,
                          'precioU' : this.precioU,
                          'tipo' : this.idCategoria,
                          'detalle' : this.detalle

                    }).then(function(response){
                        me.cerrarModal();
                        me.listarInsumos(1,'','nombre');
                    }).catch(function(error){
                        console.log(error);
                    })

              },

              desactivarInsumos(id){
             swal({
                title: 'Esta seguro de desactivar este insumo?',
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

                    axios.put('/insumos/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarInsumos(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El insumo ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function(error){
                        console.log(error);
                    })
                            
                        } else {
                            
                        }
                        });
              },
               activarInsumos(id){
             swal({
                title: 'Esta seguro de activar este insumo?',
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

                    axios.put('/insumos/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarInsumos(1,'','nombre');
                        swal(
                        'Activado!',
                        'El insumo ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function(error){
                        console.log(error);
                    })
                            
                        } else {
                            
                        }
                        });
              },

              validar(){
               this.errorInsumos= 0;
               this.MostrarErrores=[];

               if(!this.nombre) this.MostrarErrores.push("El campo nombre no puede estar vacio");
               if(this.idCategoria==0) this.MostrarErrores.push("Debe seleccionar una categoria");
               if(!this.unidad) this.MostrarErrores.push("ingrese una unidad de medida");
               if(!this.precioU) this.MostrarErrores.push("ingrese el precio por unidad minima");
               
               if (this.MostrarErrores.length) this.errorInsumos =1;
               return this.errorCategoria;
               


              },
               
               cambiarPagina(page,buscar,criterio){
               let me =this;
               me.pagination.current_page=page;
               me.listarInsumos(page,buscar,criterio);


               },
              cerrarModal(){
                    this.modal=0;
                    this.nombre='',
                    this.cantidad='',
                    this.unidad='',
                    this.precioU='',
                    this.tipo='',
                    this.detalle='';
                    this.errorInsumos=0;
                                    

              },
              abrirModal(modelo,accion,data=[]){
                      switch(modelo) {
                         case "insumos":
                         {
                             switch(accion){
                                 case 'registrar':{
                                this.tituloModal='Registrar Insumos'
                                this.modal =1;
                                this.nombre='',
                                this.cantidad=0,
                                this.unidad='',
                                this.precioU='',
                                this.tipo='',
                                this.detalle='';
                                this.tipoAction=1;
                                break;


                                 }
                                 case 'actualizar':{
                                     console.log(data);
                                     this.modal=1;
                                     this.tituloModal='Actualizar datos de insumo'
                                     this.tipoAction=2;
                                     
                                        this.nombre=data['nombre'];
                                        this.cantidad=data['cantidad'];
                                        this.unidad=data['unidad'];
                                        this.precioU=data['precioU'];
                                        this.tipo=data['tipo'];
                                        this.detalle=data['detalle'];
                                        this.insumo_id=data['id'];
                                        
                                     break;
                                 }
                             }

                         }

                           this.selectCategoria();

                        
                      }

              }
            
         },
        mounted() {
            this.listarInsumos(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }

  .mostrar {
          
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
      font-weight:bold;
  }

  .trans{
  width: 1000px;
  
  display: flex;
  justify-content: center;
}

</style>
