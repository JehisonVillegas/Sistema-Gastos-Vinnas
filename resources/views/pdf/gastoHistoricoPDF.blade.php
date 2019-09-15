<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de todos sus gastos hasta la fecha</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }

        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }
        #logo2{
        float: center;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }
        #imagen{
        width: 100px;
        }

        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #572364;
        text-align: left;
        border-bottom: 1px solid #572364;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background:#572364;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
        padding: 20px;
        background:#572364;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>

    <?php
        $totalF= $total;
    ?>
    <body>
       
        <header>
            <div id="logo">
               
            </div>
            <div id="datos">
                <p id="encabezado">
                
                    <b>Sistema de gestion de gastos Viñas</b><br>Informe de todos los gastos efectuados<br>
                </p>
            </div>
            <div id="fact">
                
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th><p id="cliente">Cuartel:Todos<br>
                            Labor:Todos<br>
                            
                            Todos los registros
                            </p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
       
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                        <th>Nombre</th>
                    <th>Cuartel</th>
                    <th>Labor</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($gastos as $a)
                <tr>
                    <td>{{$a->nombreG}}</td>
                    <td>{{$a->nombreCuartel}}</td>
                    <td>{{$a->nombreCategoria}}</td>
                    <td>{{$a->fecha_hora}}</td>
                    <td style="text-align:right;">{{$número_formato_inglés = number_format($a->Total,0,'','.')}}</td>
                    
                    
                </tr>
                @endforeach    
                    </tbody>
                    
                    <tfoot>
                       
                      
                       
                        <tr>
                       
                            <th></th>
                            <th></th>
                            <th></th>
                            <br>
                       <br>
                       <br>
                            <th>TOTAL</th>
                            
                            <td style="text-align:right;"><strong> $ {{ $número_formato_inglés = number_format($totalF,0,'','.') }}</strong></td>
                        </tr>
                       
                    </tfoot>
                </table>
            </div>
        </section>
       
        <footer>
            <div id="gracias">
                <p><b>Fecha Emision {{now()}}</b></p>
            </div>
        </footer>
    </body>
</html>