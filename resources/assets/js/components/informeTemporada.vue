<template>
   <main class="main">
          <div class="container-fluid">
         <div class="card">
          <div class="class-header">
            <div class="card-body">
          <div class="row">
               <div class="col-md-6">
                  <div class="card card-chart">
                   <div class="card-header">
                       <h4>Gastos de su Temporada Actual desde <td v-text="fecha_temp_actual + '  al día de hoy'"></td></h4>
                    </div>

                    
                       <div class="col-md-12">

           <button type="button" class="btn btn-primary" @click=" obtenerPDFTemporadaActual()">Generar Informe</button>
       </div>
                       <div class="card-footer">
                           
                       </div>
                  </div>

               </div>
           <div class="col-md-6">
            <div class="card card-chart">
                   <div class="card-header">
                       <h4>Informe Entre Fechas</h4>
                    </div>
                       <div class="card-content">
                         <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
         <div class="form-group">
                   <div class="col-md-12">
                                  <label for="">Inicio de Temporada</label>
                                <input type="date" class="form-control" v-model="fecha_hora_inicio">

                             </div>
                            <br>
                            <br>
                            <br>
                             <div class="col-md-12">
                                  <label for="">Final de Temporada</label>
                                <input type="date" class="form-control" v-model="fecha_hora_termino">

                             </div>
                             </div>
       </form>
       <div class="col-md-12">
                               <div v-show="errorFechas"  class="form-group row div-error">
                                  <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsj" :key="error" v-text="error"></div>

                                      </div>
                                  </div>

                           </div>
       <div class="col-md-12">

           <button type="button" class="btn btn-primary" @click=" obtenerPDF()">Generar Informe</button>
       </div>

                       </div>
                       <div class="card-footer">
                           <p>Gastos efectuados en los ultimo meses</p>
                       </div>
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
                fecha_hora_inicio:'',
                fecha_hora_termino:'',
                fecha_temp_actual:'',
                errorFechas:0,
                errorMostrarMsj:[]


            }

         },
        
         methods :{


                
              obtenerPDF(){
                      if (this.validarFechas()){
                    return;
                }
                    let me=this;
                var url= '/gasto/gastoTemporada?fecha_hora_inicio=' + this.fecha_hora_inicio + '&fecha_hora_termino='+ this.fecha_hora_termino;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                     window.open(respuesta,'_blank');
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
                
                if (!this.fecha_hora_inicio||this.fecha_hora_inicio>fechaActual) this.errorMostrarMsj.push("Debe seleccionar una fecha de inicio valida");
                if (!this.fecha_hora_termino) this.errorMostrarMsj.push("Debe seleccionar una fecha de termino ");
                if (this.fecha_hora_inicio&&this.fecha_hora_termino&&this.fecha_hora_inicio>this.fecha_hora_termino) this.errorMostrarMsj.push("la fecha de inicio no puede superar a la de termino");
                
                
                if(this.errorMostrarMsj.length) this.errorFechas = 1;
                return this.errorFechas;
            },

              obtenerPDFTemporadaActual(){
                      
                    let me=this;
                var url= '/gasto/gastosTemporadaActual';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                     window.open(respuesta,'_blank');
                })
                .catch(function (error) {
                    console.log(error);
                });

              },
              ObtenerFecha(){
                    let me=this;
                var url= '/gasto/obtenerFechaTempActual';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                     me.fecha_temp_actual=respuesta.fechaInicio;
                })
                .catch(function (error) {
                    console.log(error);
                });

              },

       

         },
          mounted() {
            this.ObtenerFecha();
        }
    }
</script>

