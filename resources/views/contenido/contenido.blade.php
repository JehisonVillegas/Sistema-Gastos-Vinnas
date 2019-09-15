    @extends('principal')
    @section('contenido')

    @if(Auth::check())
         @if(Auth::user()->idrol == 2)
        <template v-if ="menu==0">
           <dashboard></dashboard>
     
        </template>
           
        <template v-if ="menu==1">
             <insumos> </insumos>
   
        </template>

        <template v-if ="menu==10">
            <categoria_insumos>  </categoria_insumos>
        </template>

        <template v-if ="menu==2">
            <categoria>  </categoria>
        </template>

        <template v-if ="menu==3">
             <cuartel></cuartel>
        </template>

        <template v-if ="menu==4">
             <gastos></gastos>
        </template>
        <template v-if ="menu==5">
             <informe_temporada></informe_temporada>
        </template>
        <template v-if ="menu==6">
             <informe_cuartel></informe_cuartel>
        </template>
        <template v-if ="menu==7">
             <graficos></graficos>
        </template>
        <template v-if ="menu==8">
             <graficos_circulares></graficos_circulares>
        </template>
        <template v-if ="menu==9">
             <informe_insumos></informe_insumos>
        </template>
        
         @elseif(Auth::user()->idrol == 1)
        <template v-if ="menu==11">
             <usuarios></usuarios>
        </template>
        @endif
        @endif
    @endsection