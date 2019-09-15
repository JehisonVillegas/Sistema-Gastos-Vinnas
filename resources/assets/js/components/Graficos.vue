<template>
    <main class="main">
      <div class="container-fluid">
         <div class="card">
          <div class="class-header">
            

          </div>
     <div class="card-body">
          <div class="row">
               <div class="col-md-6">
                  <div class="card card-chart">
                   <div class="card-header">
                       <h4>Primer Intervalo <td v-text="fecha_hora_inicio"></td>--<td v-text="fecha_hora_termino"></td></h4>
                    </div>
                       <div class="card-content">
                        <div class="ct-chart">
                       
                       <template v-if="grafico1==0">
                           <div>
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
                            <br>
                            <br>
                            <div class="col-md-12">

                                <button type="button" class="btn btn-primary" @click="getGastos()">Generar Grafico</button>
                            </div>
                                                </div>
                                            </template>


                        <template v-if="grafico1==1">
                            <div>
                                <canvas id="gastos">

                                 </canvas>
                                 <div class="card-footer">
                           <p>Gastos efectuados durante los meses del intervalo</p>
                           <button type="button" class="btn btn-primary" @click="atras1()">Atras</button>
                       </div>
                            </div>
                        </template>

                        </div>

                       </div>
                       
                  </div>

               </div>
           <div class="col-md-6">
           
            <div class="card card-chart">
                   <div class="card-header">
                        <h4>Segundo Intervalo <td v-text="fecha_hora_inicio2"></td>--<td v-text="fecha_hora_termino2"></td></h4>
                    </div>
                       <div class="card-content">
                        <div class="ct-chart">
                       
                       <template v-if="grafico2==0">
                           <div>
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
         <div class="form-group">
                   <div class="col-md-12">

                       

                                  <label for="">Inicio de Temporada</label>
                                <input type="date" class="form-control" v-model="fecha_hora_inicio2">

                             </div>
                            <br>
                            <br>
                            <br>
                             <div class="col-md-12">
                                  <label for="">Final de Temporada</label>
                                <input type="date" class="form-control" v-model="fecha_hora_termino2">

                             </div>
                             </div>
                            </form>
                            <br>
                            <br>
                            <div class="col-md-12">

                                <button type="button" class="btn btn-primary" @click="getGastos2()">Generar Grafico</button>
                            </div>
                                                </div>
                                            </template>









                        <template v-if="grafico2==1">
                            <div>
                                <canvas id="gastos2">

                                 </canvas>
                                <div class="card-footer">
                           <p>Gastos efectuados durante los meses del intervalo</p>
                            <button type="button" class="btn btn-primary" @click="atras2()">Atras</button>
                       </div>
                            </div>
                        </template>

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
            grafico1:0,
            grafico2:0,
            varGasto:null,
            charGasto:null,
            gastos:[],
            varTotalGasto:[],
            varMesGasto:[],
            fecha_hora_inicio:'',
            fecha_hora_termino:'',
            fecha_hora_inicio2:'',
            fecha_hora_termino2:'',
            varGasto2:null,
            charGasto2:null,
            gastos2:[],
            varTotalGasto2:[],
            varMesGasto2:[],
            formateado:'',
            formateado2:''

         }

    },
     methods :{
         getGastos(){
            
            let me=this;
             me.grafico1=1;
            var url='/gasto/GraficoGastos?fecha_hora_inicio=' + this.fecha_hora_inicio + '&fecha_hora_termino='+ this.fecha_hora_termino;
            axios.get(url).then(function(response){
                   var respuesta= response.data;
                   me.gastos= respuesta.gastos;

                   me.loadGastos();
                   



            })
               .catch(function(error){

                   console.log(error);
               });
         },

      getGastos2(){
            
            let me=this;
             me.grafico2=1;
            var url='/gasto/GraficoGastos2?fecha_hora_inicio2=' + this.fecha_hora_inicio2 + '&fecha_hora_termino2='+ this.fecha_hora_termino2;
            axios.get(url).then(function(response){
                   var respuesta= response.data;
                   me.gastos2= respuesta.gastos;

                   me.loadGastos2();
                   



            })
               .catch(function(error){

                   console.log(error);
               });
         },
     
     loadGastos(){
   let me=this;
       var tablaMeses=[];
       var mesFormateado=[];
       var meses=['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
       me.gastos.map(function(x){
            tablaMeses.push(x.mes);
            
            me.formateado=x.total;
            me.varTotalGasto.push(me.formateado);

       });
       for(var i=0; i<tablaMeses.length;i++){
               mesFormateado.push(meses[tablaMeses[i]]);
              

            }
            me.varMesGasto=mesFormateado;
       me.varGasto=document.getElementById('gastos').getContext('2d');
         me.charGasto = new Chart(me.varGasto, {
    type: 'bar',
    data: {
        labels: me.varMesGasto,
        datasets: [{
            label: 'Gastos',
            data: me.varTotalGasto,
            backgroundColor: 'rgba(3, 118, 195, 0.5)',
                
            borderColor: 'rgba(0, 158, 219, 1)',
                
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
     },

      loadGastos2(){
       let me=this;
       var tablaMeses2=[];
       var mesFormateado2=[];
       var meses2=['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
       me.gastos2.map(function(x){
            tablaMeses2.push(x.mes);
            
            me.formateado2=x.total;
            me.varTotalGasto2.push(me.formateado2);

       });
       for(var i=0; i<tablaMeses2.length;i++){
               mesFormateado2.push(meses2[tablaMeses2[i]]);
              

            }
            me.varMesGasto2=mesFormateado2;
       me.varGasto2=document.getElementById('gastos2').getContext('2d');
         me.charGasto2 = new Chart(me.varGasto2, {
    type: 'bar',
    data: {
        labels: me.varMesGasto2,
        datasets: [{
            label: 'Gastos',
            data: me.varTotalGasto2,
            backgroundColor: 'rgba(255, 0, 0, 0.25)',
                
            borderColor: 'rgba(0, 158, 219, 1)',
                
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
     },

     atras1(){

         this.grafico1=0;
         this.fecha_hora_inicio='';
         this.fecha_hora_termino='';
         this.varGasto=null;
         this.charGasto=null;
            this.gastos=[];
            this.varTotalGasto=[];
            this.varMesGasto=[];
     },

     atras2(){
         this.grafico2=0;
         this.fecha_hora_inicio2='';
         this.fecha_hora_termino2='';
         this.varGasto2=null;
         this.charGasto2=null;
            this.gastos2=[];
            this.varTotalGasto2=[];
            this.varMesGasto2=[];
     }


     },

     mounted(){

         

     }
}
</script>