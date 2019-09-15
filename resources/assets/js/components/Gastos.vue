<template>
            <main class="main">
            <!-- Breadcrumb -->
           <ol class="breadcrumb">
               <h3 align="center">Gastos de su Viña</h3> 
            </ol>
            
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <div class="trans">
                        <button type="button" @click="mostrarDetalle()" class="btn btn-primary">
                            <i class="icon-plus"></i>  Ingresar nuevo Gasto
                        </button>
                        </div>
                    </div>
                    <!--listado-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombreG">Nombre</option>
                                      <option value="idCuartel">Cuartel</option>
                                      <option value="nombreCategoria">Categoria</option>
                                      <option value="fecha_hora">Fecha</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarGasto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarGasto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Cuartel</th>
                                    <th>Labor</th>
                                    
                                    
                                    <th>Total $</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="gasto in arrayGastos" :key="gasto.id">
                                    <td>
                                        <button type="button" @click="verGasto(gasto.id)" class="btn btn-success btn-sm">
                                          <i class="icon-eye"></i>
                                        </button> &nbsp;
                                        
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarGasto(gasto.id)">
                                                <i class="icon-trash"></i>
                                            </button>&nbsp;
                                        
                                        
                                            <button type="button" class="btn btn-info" @click="Inicio_temporada(gasto.id)">
                                                <i class="fa fa-check-circle"></i>
                                            </button>
                                        
                                       
                                    </td>
                                    <td v-text="gasto.nombreG"></td>
                                    <td v-text="gasto.nombreCuartel"></td>
                                    <td v-text="gasto.nombreCategoria"></td>
                                    
                                    
                                    <td v-text="formatPrice(gasto.Total)"></td>
                                    <td v-text="gasto.fecha_hora"></td>
                                   
                                    <td>
                                        
                                        <div v-if="gasto.estado=='Inicio'">
                                            <span class="badge badge-info">Inicio</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-success">Creado</span>
                                        </div>
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        </div>
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
                                <li>
                                  <button type="button" @click="cargarPDF()" class="btn btn-info">
                            <i class="icon-pdf"></i> Generar informe Historico

                        </button>
                         <strong>Puede cambiar su inicio de temporada presionando el boton con el icono de tick</strong>
                                </li>
                            </ul>
                            
                        </nav>
                    </div>
                    </template >
                    <!--detalle-->
                    <template v-else-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-4">
                               <div class="form-group">
                                   <label for="">Cuartel</label>
                                   <select class="form-control" v-model="idCuartel">
                                     <option value="0" disabled>Seleccione Cuartel</option>
                                     <option  v-for="cuartel in arrayCuartels" :key="cuartel.id" :value="cuartel.id" v-text="cuartel.nombreCuartel"></option>
                                   </select>
                               </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                   <label for="">Labor</label>
                                    <select class="form-control" v-model="idCategoria">
                                      <option value="0" disabled>Seleccione Labor</option>  
                                      <option v-for="categoria in arrayCategorias" :key="categoria.id" :value="categoria.id" v-text="categoria.nombreCategoria"></option>
                                    </select>
                                  </div>
                                </div>
                                
                            
                            <div class="col-md-4">
                                <label for="">Nombre</label>
                                <input type="text" class="form-control" v-model="nombreG">
                            </div> 
                        
                             

                             
                             <div class="col-md-4">
                                  <label for="">Fecha</label>
                                <input type="date" class="form-control" v-model="fecha_hora">

                             </div>
                           <div class="col-md-12">
                               <div v-show="errorGasto"  class="form-group row div-error">
                                  <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsjGasto" :key="error" v-text="error"></div>

                                      </div>
                                  </div>

                           </div>
                          </div>
                          <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label > Insumo <span style="color:red;" v-show="idinsumo==0">*Seleccione</span></label>
                                     <div class="form-inline">
                                       <input tipe="text" class="form-control" v-model="nombreIns" @keyup.enter="buscarInsumo()" placeholder="ingrese insumo"> 
                                       <button @click="abrirModal()"    class="btn btn-primary">Listado</button>
                                       <input type="text" readonly class="form-control" v-model="insumos">
                                   </div>
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                      <label >Precio</label>
                                      <input type="text" readonly class="form-control" v-model="precioU">

                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label >Unidad</label>
                                      <input type="text" readonly class="form-control" v-model="unidadMedida">

                                    </div>

                                </div>
                         <div class="col-md-4">
                                    <div class="form-group">
                                      <label >Cantidad <span style="color:red;" v-show="Cantidad==0">*Seleccione</span></label>
                                      <input type="number" value="0" class="form-control" v-model="Cantidad">

                                    </div>

                                </div>
                            <div class="col-md-2">
                                <div class="form-group">

                                    <button @click="agregarDetalle()"    class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                          </div>

                          <div class="form-group row border">
                             <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                  <thead>
                                     <tr>
                                       <th>Opciones</th>
                                       <th>Insumo</th>
                                       <th>Precio</th>
                                       <th>Unidad</th>
                                       <th>Cantidad</th>
                                       <th>Total</th>

                                     </tr>

                                  </thead>
                                <tbody v-if="arrayDetalle.length">
                                 <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                 <td>
                                      <button @click="eliminarDetalle(index)"   class="btn btn-danger btn-sm">
                                          <i class="icon-close"></i>
                                      </button>      
                                </td>
                                 <td v-text="detalle.insumo">
                                    
                                 </td>
                                 <td>
                                     <input v-model="detalle.precio" type="number" class="form-control">
                                 </td>
                                 <td v-text="detalle.unidad">

                                 </td>
                                 <td>
                                     <input v-model="detalle.Cantidad" type="number"  class="form-control">
                                 </td>
                                <td>
                                   $ {{detalle.precio*detalle.Cantidad}}
                                </td>
                                
                                 </tr>
                                 

                                  <tr style="background-color: ·CEECF5;">
                                   <td colspan="5" align="rigth"><strong>Total  </strong></td>
                                   <td>$ {{TotalFinal=calcularTotal}}</td>
                                 </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                      <td colspan="5">
                                      No hay insumos Agregados

                                      </td>

                                    </tr>

                                </tbody>
                                </table>

                             </div>
                          </div>
                          <div class="form-group row border">
                              <div clas="col-md-12">
                              <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                              <button type="button" class="btn btn-primary" @click="registrarGasto()">Ingresar Gasto</button>
                           </div>
                          </div>


                    </div>
                    </template>

                    <!-- ver detalle gasto-->

                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-4">
                               <div class="form-group">
                                   <label for="">Cuartel</label>
                                   <p v-text="cuartel"></p>
                               </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                   <label for="">Labor</label>
                                    <p v-text="labor"></p>
                                  </div>
                                </div>
                                
                            
                            <div class="col-md-4">
                                <label for="">Nombre</label>
                                <p v-text="nombreG"></p>
                            </div> 
                        
                             

                             
                             <div class="col-md-4">
                                  <label for="">Fecha</label>
                                <p v-text="fecha_hora"></p>

                             </div>
                        </div>
                    
                    

                          <div class="form-group row border">
                             <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                  <thead>
                                     <tr>
                                       
                                       <th>Insumo</th>
                                       <th>Precio</th>
                                       <th>Unidad</th>
                                       <th>Cantidad</th>
                                       <th>Total</th>

                                     </tr>

                                  </thead>
                                <tbody v-if="arrayDetalle.length">
                                 <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                 
                                 <td v-text="detalle.insumo">
                                    
                                 </td>
                                 <td v-text="detalle.precio">
                                    
                                 </td>
                                 <td v-text="detalle.unidad">

                                 </td>
                                 <td v-text="detalle.Cantidad">
                                    
                                 </td>
                                <td>
                                   $ {{detalle.precio*detalle.Cantidad}}
                                </td>
                                
                                 </tr>
                                 

                                  <tr style="background-color: ·CEECF5;">
                                   <td colspan="4" align="rigth"><strong>Total  </strong></td>
                                   <td>$ {{TotalFinal}}</td>
                                 </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                      <td colspan="5">
                                      No hay insumos Agregados

                                      </td>

                                    </tr>

                                </tbody>
                                </table>

                             </div>
                          </div>
                          <div class="form-group row border">
                              <div clas="col-md-12">
                              <button type="button" @click="ocultarDetalle()"  class="btn btn-secondary">Cerrar</button>
                              
                           </div>
                          </div>


                    </div>
                    </template>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#572364;">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                             <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                      <option value="nombre">Nombre</option>
                                      <option value="detalle">detalle</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup.enter="listarInsumos(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarInsumos(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                            <div class="table-responsive">
                               <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                   
                                    <th>Nombre</th>
                                    
                                    
                                    <th>Categoria</th>
                                    <th>Unidad</th>
                                    <th>Precio unitario</th>
                                     <th>Opciones</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="insumos in arrayInsumos" :key="insumos.id">
                                   
                                    <td v-text="insumos.nombre"></td>
                                    
                                    <td v-text="insumos.nombreCategoriaInsumo"></td>
                                    <td v-text="insumos.unidad_de_medida"></td>
                                    <td v-text="insumos.Precio_Unitario"></td>
                                     <td>
                                        <button type="button" @click="agregarDetalleModal(insumos)" class="btn btn-success btn-sm" >
                                          Agregar
                                        </button> 
                                    
                                    </td>
                                    <!--
                                    <td>

                                        <div v-if="insumos.condicion">
                                        <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                             <span class="badge badge-danger">desactivado</span>
                                             </div>
                                    </td>
                                    -->
                                </tr>
                            </tbody>
                        </table>


                            </div>


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
</template>
<script>
  

    export default {
        data (){
            return {
                gasto_id: 0,
                idCuartel:0,
                cuartel:'',
                idCategoria:0,
                labor:'',
                nombreG : '',
                criterioA:'nombre',
                buscarA:'',
                Total:0,
                TotalFinal:0.0,
                fecha_hora:'',
                
                arrayDetalle :[],
                arrayGastos: [],
                arrayCuartels:[],
                arrayCategorias:[],
                modal : 0,
                listado:1,
                tituloModal : '',
                tipoAccion : 0,
                errorGasto : 0,
                errorMostrarMsjGasto : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombreG',
                buscar : '',
                mostrarInsumo:'',
                arrayInsumos : [],
                idinsumo:0,
                insumos:'',
                Cantidad:0,
                nombreIns:'',
                precioU:0,
                unidadMedida:''
                

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

            },

            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].Cantidad)

                }
               return resultado;
            }

            
        },
        methods : {

             formatPrice(value) {
         let val = (value/1).toFixed(0).replace('.')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },

              cargarPDF(){
                   
                    /*let me=this;
                var url= 'gasto/gastoHistoricoPDF';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                     window.open(respuesta,'_blank');
                })
                .catch(function (error) {
                    console.log(error);
                });
*/
                 window.open('http://localhost:8000/gasto/gastoHistoricoPDF');

              },
            selectCuartel(){
                 let me=this;
                var url= '/cuartel/selectcuartel';
                axios.get(url).then(function (response) {
                   var respuesta= response.data;
                   me.arrayCuartels=respuesta.cuarteles;
                  
                   
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           selectCategoria(){
            let me=this;
                var url= '/categoria/selectCategoria';
                axios.get(url).then(function (response) {
                   var respuesta= response.data;
                   me.arrayCategorias=respuesta.categorias;
                 
                   
                })
                .catch(function (error) {
                    console.log(error);
                });

           },

           buscarInsumo(){
               let me=this;
               var url='/insumos/buscarInsumo?filtro=' + me.nombreIns;
               axios.get(url).then(function(response){
                   var respuesta= response.data;
                   me.arrayInsumos=respuesta.insumos;
                if(me.arrayInsumos.length>0){
                    me.insumos=me.arrayInsumos[0]['nombre'];
                    me.idinsumo=me.arrayInsumos[0]['id'];
                    me.precioU=me.arrayInsumos[0]['Precio_Unitario'];
                    me.unidadMedida=me.arrayInsumos[0]['unidad_de_medida']
                }else{
                    me.insumos='No existe insumo';
                    me.idinsumo=0;

                }

               })
               .catch(function(error){

                   console.log(error);
               })
           },
           revisa(id){
                    var sw=0;
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        if(this.arrayDetalle[i].idInsumo==id){
                            sw=true;
                        }
                    }
                   return sw;
               },
             agregarDetalle(){
               let me=this;
               if(me.idinsumo==0 || me.Cantidad==0 || me.precioU==0){

               }else{

                   if(me.revisa(me.idinsumo)){
                       swal({
                            type:'error',
                            title:'Error en la operacion',
                            text:'Este insumo ya fue ingresado',
                       })

                   }else{
               me.arrayDetalle.push({
                  idInsumo: me.idinsumo,
                  insumo: me.insumos,
                  Cantidad: me.Cantidad,
                  precio: me.precioU,
                  unidad:me.unidadMedida
               
               })
                   }
                 me.nombreIns="";
                 me.idinsumo=0;
                 me.Cantidad=0;
                 me.precioU=0;
                 me.insumos="";

               }
             },

             listarInsumos(buscar,criterio){
                  let me=this;
                  var url='/insumos/listarInsumos?buscar='+ buscar + '&criterio='+ criterio;
                 axios.get(url).then(function (response) {
                     var respuesta= response.data;
                   me.arrayInsumos = respuesta.insumos.data;
                   
                    
                }).catch(function (error) {console.log(error);
                });


              }, 
               agregarDetalleModal(data=[]){
                    let me=this;
              

                   if(me.revisa(data['id'])){
                       swal({
                            type:'error',
                            title:'Error en la operacion',
                            text:'Este insumo ya fue ingresado',
                       })

                   }else{
               me.arrayDetalle.push({
                  idInsumo: data['id'],
                  insumo: data['nombre'],
                  Cantidad: 1,
                  precio: data['Precio_Unitario'],
                  unidad:data['unidad_de_medida']
               
               })
                   
               }
             
               },
               eliminarDetalle(index){
                  let me=this;

                  me.arrayDetalle.splice(index,1);

               },

            listarGasto (page,buscar,criterio){
                let me=this;
                var url= '/gasto?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayGastos = respuesta.gastos.data;
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
                me.listarGasto(page,buscar,criterio);
            },
            registrarGasto(){
                if (this.validarGasto()){
                    return;
                }
                
                let me = this;

                axios.post('/gasto/ingresar',{
                    'nombre': this.nombreG,
                    'cuartel': this.idCuartel,
                    'categoria':this.idCategoria,
                    'fecha':this.fecha_hora,
                    'total':this.TotalFinal,

                    'data' :this.arrayDetalle
                }).then(function (response) {
                    me.listado=1;
                    me.listarGasto(1,'','nombre');
                    me.idCuartel=0;
                    me.idCategoria=0;
                    me.nombreG='';
                    me.fecha_hora='';
                    me.TotalFinal=0.0;
                    me.idinsumo=0;
                    me.insumo='';
                    me.Cantidad=0;
                    me.precioU=0;
                    me.arrayDetalle=[];


                }).catch(function (error) {
                    console.log(error);
                });
            },
            
            desactivarGasto(id){
               swal({
                title: 'Esta seguro de anular este gasto?',
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

                    axios.put('/gasto/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarGasto(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El gasto ha sido desactivado con éxito.',
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
            Inicio_temporada(id){
               swal({
                title: 'Desea que este Gasto sea su inicio de temporada?',
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

                    axios.put('/gasto/inicio_temporada',{
                        'id': id
                    }).then(function (response) {
                        me.listarGasto(1,'','nombre');
                        swal(
                        'Hecho!',
                        'Inicio de temporada Establecido',
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



            validarGasto(){
                this.errorGasto=0;
                this.errorMostrarMsjGasto=[];
                
                var fecha = new Date(); //Fecha actual
  var mes = fecha.getMonth()+1; //obteniendo mes
  var dia = fecha.getDate(); //obteniendo dia
  var ano = fecha.getFullYear(); //obteniendo año
  if(dia<10)
    dia='0'+dia; //agrega cero si el menor de 10
  if(mes<10)
    mes='0'+mes //agrega cero si el menor de 10
           var fechaActual=ano+"-"+mes+"-"+dia;
                if (this.idCuartel==0) this.errorMostrarMsjGasto.push("Debe seleccionar un cuartel");
                //if (this.Cantidad<1) this.errorMostrarMsjGasto.push("Debe seleccionar una cantidad mayor a 1");
                if (!this.nombreG) this.errorMostrarMsjGasto.push("Debe ingresar un nombre");
                if (this.idCategoria==0) this.errorMostrarMsjGasto.push("Debe seleccionar una labor");
                if (this.fecha_hora>fechaActual) this.errorMostrarMsjGasto.push("Debe seleccionar una fecha menor o igual a la actual");
                if (!this.fecha_hora) this.errorMostrarMsjGasto.push("Debe seleccionar una fecha");
                if (this.arrayDetalle.length<=0) this.errorMostrarMsjGasto.push("Debe ingresar insumos");
                if(this.errorMostrarMsjGasto.length) this.errorGasto = 1;
                return this.errorGasto;
            },
            
            mostrarDetalle(){
                this.listado=0;
                this.selectCuartel();
                this.selectCategoria();
                let me=this;
                me.idCuartel=0;
                    me.idCategoria=0;
                    me.nombreG='';
                    me.fecha_hora='';
                    me.TotalFinal=0.0;
                    me.idinsumo=0;
                    me.insumo='';
                    me.Cantidad=0;
                    me.precioU=0;
                    me.arrayDetalle=[];

            },
            ocultarDetalle(){
               this.listado=1;
               this.errorMostrarMsjGasto = [];
            
            },
             verGasto(id){
                 this.listado=2;
                 let me=this;
              //obtener el ingreso
              var arrayGastoT=[];
             var url= '/gasto/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayGastoT = respuesta.gasto;
                    me.cuartel=arrayGastoT[0]['nombreCuartel'];
                    me.labor=arrayGastoT[0]['nombreCategoria'];
                    me.nombreG=arrayGastoT[0]['nombreG'];
                    me.fecha_hora=arrayGastoT[0]['fecha_hora'];
                    me.TotalFinal=arrayGastoT[0]['Total'];


                })
                .catch(function (error) {
                    console.log(error);
                });
// obtener detalles

var urld= '/gasto/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                   


                })
                .catch(function (error) {
                    console.log(error);
                });


             },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                
            },
            abrirModal(){
                                this.arrayInsumos=[]
                                this.modal = 1;
                                this.tituloModal = 'Seleccione uno o mas Insumos Utilizados';
                               
            }
        },
        mounted() {
            this.listarGasto(1,this.buscar,this.criterio);
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
    @media (min-width:600px){

        .btnagregar{

            margin-top: 2rem;
        }
        .trans{
  width: 1000px;
  
  display: flex;
  justify-content: center;
}
    }
</style>