<template>
    <main class="main">
      <div class="container-fluid">
         <div class="card">
          <div class="class-header">
            

          </div>
     <div class="card-body">
          <div class="row">
              
           <div class="col-md-12">
            <div class="card card-chart">
                   <div class="card-header">
                       <h4>Gráfico Gasto de sus insumos utilizados  en su temporada actual</h4>
                    </div>
                       <div class="card-content">
                        <div class="ct-chart">
                        <canvas id="gastos">

                        </canvas>

                        </div>

                       </div>
                       <div class="card-footer">
                           <p>Gráfico de los insumos utilizados durante la temporada Actual</p>
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
                feha_termino_L:''
            }

         },
        
         methods :{

              getGastos(){
            let me=this;
            var url='/gasto/graficoInsumosTemporada';
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
            me.varCuarteles.push(x.nombreCategoriaInsumo);
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


            

             obtenerPDFLabor(){
                      
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