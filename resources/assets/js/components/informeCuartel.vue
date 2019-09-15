<template>
    <main class="main">
      <div class="container-fluid">
         <div class="card">
          <div class="class-header">
            

          </div>
     <div class="card-body">
          <div class="row">
               <div class="col-md-4">
                  <div class="card card-chart">
                   <div class="card-header">
                       <h4>Gastos por Labor</h4>
                    </div>
                       <div class="card-content">
                        <div class="ct-chart">
                           <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
         <div class="form-group">
                   <div class="col-md-12">

                        <div class="form-group">
                                  <label for="">Labor</label>
                                    <select class="form-control" v-model="idCategoria">
                                      <option value="0" disabled>Seleccione una categoria de</option>  
                                      <option v-for="categoria in arrayCategorias" :key="categoria.id" :value="categoria.id" v-text="categoria.nombreCategoria"></option>
                                    </select>
                               </div>

                                  <label for="">Inicio de Temporada</label>
                                <input type="date" class="form-control" v-model="fecha_inicio_L">

                             </div>
                            <br>
                            <br>
                            <br>
                             <div class="col-md-12">
                                  <label for="">Final de Temporada</label>
                                <input type="date" class="form-control" v-model="feha_termino_L">

                             </div>
                             </div>
       </form>

       <br>
       <div class="col-md-12">
                               <div v-show="errorFechas"  class="form-group row div-error">
                                  <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsj" :key="error" v-text="error"></div>

                                      </div>
                                  </div>

                           </div>
       <br>
       <div class="col-md-12">

           <button type="button" class="btn btn-primary" @click=" obtenerPDFLabor()">Generar Informe</button>
       </div>

                        </div>

                       </div>
                       <div class="card-footer">
                         
                       </div>
                  </div>

               </div>
           <div class="col-md-8">
            <div class="card card-chart">
                   <div class="card-header">
                       <h4>Gráfico Gasto de sus Labores (Temporada Actual)</h4>
                    </div>
                       <div class="card-content">
                        <div class="ct-chart">
                        <canvas id="gastos">

                        </canvas>

                        </div>

                       </div>
                       <div class="card-footer">
                           
                       </div>
                  </div>

               </div>
                  
                  
            </div>
            </div>

            
           
          </div>

     </div>
         
         

     
        
 
    </main>
</template>


<script>


    export default {
         data(){
            return{

                varGasto:null,
            charGasto:null,
            gastos:[],
            varTotalGasto:[],
            varCuarteles:[],
            total:'',
            totalH:'',
               
                categorias:'',
                arrayCategorias:[],
                idCategoria:0,
                fecha_inicio_L:'',
                feha_termino_L:'',
                errorFechas:0,
                errorMostrarMsj:[]
            }

         },
        
         methods :{

              getGastos(){
            let me=this;
            var url='/gasto/graficoLabores';
            axios.get(url).then(function(response){
                   var respuesta= response.data;
                   me.gastos= respuesta.gastos;

                   me.loadGastos();



            })
               .catch(function(error){

                   console.log(error);
               });
         },

    
     
     loadGastos(){
       let me=this;
       me.gastos.map(function(x){
            me.varCuarteles.push(x.nombreCategoria);
            me.varTotalGasto.push(x.total);

       });
       me.varGasto=document.getElementById('gastos');
         me.charGasto = new Chart(me.varGasto, {
    type: 'pie',
    data: {
        labels: me.varCuarteles,
        datasets: [{
            label: 'Gastos',
            data: me.varTotalGasto,
            backgroundColor: [

                "#FF6384",
                "#800080",
                "#84FF63",
                "#8463FF",
                "#6384FF",
                "#00FFFF"
            ]
        }]
    },
    
        
    
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
             validarFechas(){
                this.errorFechas=0;
                this.errorMostrarMsj=[];
                
                var fecha = new Date(); //Fecha actual
  var mes = fecha.getMonth()+1; //obteniendo mes
  var dia = fecha.getDate(); //obteniendo dia
  var ano = fecha.getFullYear(); //obteniendo año
  if(dia<10)
    dia='0'+dia; //agrega cero si el menor de 10
  if(mes<10)
    mes='0'+mes //agrega cero si el menor de 10
           var fechaActual=ano+"-"+mes+"-"+dia;
                if (this.idCategoria==0) this.errorMostrarMsj.push("Debe seleccionar una labor");
                if (!this. fecha_inicio_L||this. fecha_inicio_L>fechaActual) this.errorMostrarMsj.push("Debe seleccionar una fecha de inicio valida");
                if (!this.feha_termino_L) this.errorMostrarMsj.push("Debe seleccionar una fecha de termino ");
                if (this. fecha_inicio_L&&this.feha_termino_L&&this. fecha_inicio_L>this.feha_termino_L) this.errorMostrarMsj.push("la fecha de inicio no puede superar a la de termino");
                
                
                if(this.errorMostrarMsj.length) this.errorFechas = 1;
                return this.errorFechas;
            },


            

             obtenerPDFLabor(){
                      if (this.validarFechas()){
                    return;
                }
                    let me=this;
                var url= '/gasto/gastos_labor?fecha_hora_inicio_L=' + this.fecha_inicio_L + '&fecha_hora_termino_L='+ this.feha_termino_L+ '&idCategoria='+ this.idCategoria;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                     window.open(respuesta,'_blank');
                })
                .catch(function (error) {
                    console.log(error);
                });

              },
                
              
              

         },

         
         mounted(){

                 this.getGastos();
                  this.selectCategoria();
              }
    }
    </script>