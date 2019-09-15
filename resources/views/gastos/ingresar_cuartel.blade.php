@extends ('principal')
  @section ('contenido')

  <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
  <div class="form-group">
    
   <div class="form-group">
                        <label class="col-sm-2 control-label">Tipo de Cepa:</label>
                          <div class="col-sm-5">
                        <div class="controls">
                            <select class="form-control" name="tipoUva" style="width:200px" >
                              <option value="Sauvignon blanc">Sauvignon blanc</option> 
                              <option value="Chardonnay" >Chardonnay</option>
                              <option value="Riesling">Riesling</option>
                              <option value="Cabernet Sauvignon">Cabernet Sauvignon</option>
                              <option value="Merlot">Merlot</option>
                              <option value="Carménère">Carménère</option>
                              <option value="Syrah">Syrah</option>
                              <option value="Pinot Noir">Pinot Noir</option>
                              </select>
                             <input type="hidden" name="_token" value="{{ csrf_token() }}"> 

                             </div>
                             </div>
                             </div>
                             

  <div class="form-group">


    <label for="CantidadP" class="col-sm-2 control-label">Cantidad de plantas</label>
    <div class="col-sm-10">
      <input type="int" class="form-control" name="CantidadP" placeholder="Cantidad" style="width:200px">
    </div>
  </div>
 
  

            <div class="form-group">
                        <label class="col-sm-2 control-label">Tipo de riego:</label>
                        <div class="col-sm-5">
                        <div class="controls">
                            <select class="form-control" name="riego" style="width:200px" >
                              <option value="Goteo">Riego por goteo</option>
                              <option value="Manual">Riego Manual</option>
                              <option value="Surcos">Riego por surcos</option>
                            </select>
                            </div>
                            </div>
                        
                      
                            </div>
                       


  <div class="form-group">
    </main>

    @stop@endsection