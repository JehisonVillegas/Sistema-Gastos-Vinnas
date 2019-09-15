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
                       <h4>Gastos por Mes</h4>
                    </div>
                       <div class="card-content">
                        <div class="ct-chart">
                        <canvas id="gastos">

                        </canvas>

                        </div>

                       </div>
                       <div class="card-footer">
                           <p>Gastos efectuados en los últimos meses </p>
                       </div>
                  </div>

               </div>
           <div class="col-md-6">
           <div class="card card-chart">
                   <div class="card-header">
                       <h4>Total Teporada Actual</h4>
                    </div>
                       <div class="card-content">
                        
                        
                           <h2 ><td v-text="'  $ '+totalFormateado"></td></h2>
                        

                       

                       </div>
                       <div class="card-footer">
                           <p>Puede cambiar el inicio de su temporada en la sección de gastos </p>
                       </div>
                  </div>
                   <div class="card card-chart">
                   <div class="card-header">
                       <h4>Último Gasto Efectuado</h4>
                    </div>
                       <div class="card-content">
                        <div class="ct-chart">
                        
                           <h2 align="center" > <td v-text="'  $ '+totalHFormateado"></td></h2>
                        

                        </div>

                       </div>
                       <div class="card-footer">
                          <h3> <td  v-text="'Proceso  : '+ nombre"></td> </h3>
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
            varMesGasto:[],
            total:'',
            totalH:'',
            nombre:'',
            totalFormateado:'',
            totalHFormateado:'',
            formateado:''
         }

    },
     methods :{
         getGastos(){
            let me=this;
            var url='/dashboard';
            axios.get(url).then(function(response){
                   var respuesta= response.data;
                   me.gastos= respuesta.gastos;

                   me.loadGastos();



            })
               .catch(function(error){

                   console.log(error);
               });
         },

      getTotal(){
          let me=this;
            var url='/gasto/total_temporada';
            axios.get(url).then(function(response){
                   var respuesta= response.data;
                   me.total= respuesta.Total;
                   me.totalH=respuesta.TotalH;
                   me.nombre=respuesta.nombre;
                 
               me.totalFormateado=new Intl.NumberFormat("de-DE").format(me.total);
               me.totalHFormateado=new Intl.NumberFormat("de-DE").format(me.totalH);

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
            
            //me.formateado=new Intl.NumberFormat("es-ES").format(x.total);
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
     }
     },

     mounted(){

         this.getGastos();
         this.getTotal();

     }
}
</script>