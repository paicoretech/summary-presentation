@extends('dashboard.base')

@section('css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="fade-in">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"><strong>Select Customer</strong> <small>Form</small></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group row">
                                    <label class="col-form-label text-center" for="select1">Select Customer</label>
                                    <select class="form-control" id="select1" name="select1">
                                        <option value="0">Please select</option>
                                        <option value="1">Option #1</option>
                                        <option value="2">Option #2</option>
                                        <option value="3">Option #3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="col-form-label" for="date-input">Date Input</label>
                                    <input class="form-control" id="date-input" type="date" name="date-input"
                                        placeholder="date"><span class="help-block">Please enter a valid date</span>
                                </div>
                            </div>
                            <div class="col-sm-3 mb-1">
                                <div class="form-group">
                                    <label class="col-form-label" for="date-input">Date Input</label>
                                    <input class="form-control" id="date-input" type="date" name="date-input"
                                        placeholder="date"><span class="help-block">Please enter a valid date</span>
                                </div>
                            </div>
                            <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                <div class="form-group ">
                                    <button id="btnGenerate" class="btn btn-primary btnGenerate" type="submit"> Generate invoice</button>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid" style="background-color: #fff">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="row main-section">
                <div class="col-md-12 col-sm-12 header">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h3><i class="fa fa-cloud" aria-hidden="true"></i> COOPERATIVA DE EMPRESARIO TAXISTAS DE
                                TELDE</h3>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2 text-right ">
                            <p>Invoice #555</p>
                            <span>May 6 ,2017</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 content">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <table id="tablePreview" class="table w-auto">
                                <!--Table head-->
                                <tbody>
                                    <tr>
                                        <th scope="row">Periodo del informe</th>
                                        <td class="text-left pl-4 ">01/08/2020&nbsp; a&nbsp; 31/08/2020
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Descripcion</th>
                                        <td class="text-left pl-4 ">telde</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Potencia de Pico</th>
                                        <td class="text-left pl-4">38.5</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Fecha de Instalacion</th>
                                        <td class="text-left pl-4">17/6/2019</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Energia periodica total de la planta</th>
                                        <td class="text-left pl-4 ">5082.030 kWh
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Descuento para el Cliente</th>
                                        <td class="text-left pl-4">20%</td>
                                    </tr>
                                </tbody>
                                <!--Table body-->
                            </table>
                            <!--Table-->
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4 text-center ">

                            <table class=" table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="table-head-price " scope="colgroup">PRECIO DE ENERGÍA
                                            RECAP (IMP. INCLUIDO) (€/kWh)</th>
                                    </tr>


                                    <tr>
                                        <th scope="col">P1</th>
                                        <th scope="col">P2</th>
                                        <th scope="col">P3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> 0.099660 € </td>
                                        <td> 0.088675 € </td>
                                        <td> 0.065319 € </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4 text-center">

                            <table class=" table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="table-head-price " scope="colgroup">PRECIO DE ENERGÍA
                                            COMERCIALIZADORA (IMP. INCLUIDO) (€/kWh)</th>
                                    </tr>


                                    <tr>
                                        <th scope="col">P1</th>
                                        <th scope="col">P2</th>
                                        <th scope="col">P3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>0.124575 €</td>
                                        <td>0.1108432 €</td>
                                        <td>0.0816487 €</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-md-6 col-sm-6 col-xs-6">

                                <table id="tablePreview" class="table w-auto">
                                    Table head
                                    <tbody>
                                        <tr>
                                            <th scope="row">Periodo del informe</th>
                                            <td class="text-left pl-4 ">01/08/2020  a  31/08/2020
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Descripcion</th>
                                            <td class="text-left pl-4 ">telde</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Potencia de Pico</th>
                                            <td class="text-left pl-4">38.5</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Fecha de Instalacion</th>
                                            <td class="text-left pl-4">17/6/2019</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Energia periodica total de la planta</th>
                                            <td class="text-left pl-4 ">5082.030 kWh
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Descuento para el Cliente</th>
                                            <td class="text-left pl-4">20%</td>
                                        </tr>
                                    </tbody>
                                    Table body
                                    </table>
                                    Table
                            </div>-->
                        <!--<div class="col-md-6 col-sm-6 col-xs-6">
                               <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-3 text-left ">
                                    
                                        <table class=" table table-bordered" >
                                            <thead >
                                                <tr>
                                                    <th colspan="3" class="table-head-price " scope="colgroup">PRECIO DE ENERGÍA COMERCIALIZADORA (IMP. INCLUIDO) (€/kWh)</th>
                                                </tr>
                                    
                                    
                                                <tr>
                                                    <th scope="col">P1</th>
                                                    <th scope="col">P2</th>
                                                    <th scope="col">P3</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>0.124575 €</td>
                                                    <td>0.1108432 €</td>
                                                    <td>0.0816487 €</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3 text-left ">
                                    
                                        <table class=" table table-bordered" >
                                            <thead >
                                                <tr>
                                                    <th colspan="3" class="table-head-price " scope="colgroup">PRECIO DE ENERGÍA RECAP (IMP. INCLUIDO) (€/kWh)</th>
                                                </tr>
                                    
                                    
                                                <tr>
                                                    <th scope="col">P1</th>
                                                    <th scope="col">P2</th>
                                                    <th scope="col">P3</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> 0.099660 € </td>
                                                    <td> 0.088675 € </td>
                                                    <td> 0.065319 € </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div> 
                                </div>
                            </div>-->
                    </div>
                </div>
                <div class="col-md-12 col-sm-12  ">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Total kWh</th>
                                <th>Total (€)</th>
                                <th>P1_Punta (kWh)</th>
                                <th>P1_Punta (€)</th>
                                <th>P2_Llano (kWh)</th>
                                <th>P2_Llano (€)</th>
                                <th>P3_Valle (kWh)</th>
                                <th>P3_Valle (€)</th>
                                {{-- <th>P1_Punta (kWh)</th>
                                  <th>P1_Punta (€)</th>
                                  <th>P2_Llano (kWh)</th>
                                  <th>P2_Llano (€)</th>
                                  <th>P3_Valle (kWh)</th>
                                  <th>P3_Valle (€)</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>COOPERATIVA DE EMPRESARIOS TAXISTAS DE TELDE</td>
                                <td>5082.03</td>
                                <td> 477.60 €</td>
                                <td>2458.56</td>
                                <td> 245.02 € </td>
                                <td> 2621.12 </td>
                                <td> 232.43 € </td>
                                <td>2.35</td>
                                <td> 0.15 €</td>
                            </tr>
                            <tr>
                                <th colspan="1">Total:</td>
                                <th>5082.03</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right col-md-12 col-sm-12">
                        <button id="printInvoice" class="btn btn-info btnGenerate"><i class="fa fa-print"></i> Print</button>
                        <button class="btn btn-info btnGenerate"><i class="fa fa-file-pdf-o"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection