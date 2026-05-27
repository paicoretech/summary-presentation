@extends('dashboard.base')

@section('css')

@endsection
@section('scripts')
    <script src="{{ asset('js/invoice.js') }}"></script>
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
                                <div class="form-group pt-2 ">
                                    <button id="btnGenerate" class="btn btn-primary btnGenerate" type="submit"> Generate
                                        invoice</button>
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
                        <div class="col-md-10 col-sm-10 col-xs-10 mt-3">
                            <h3><i class="fa fa-cloud" aria-hidden="true"></i> COOPERATIVA DE EMPRESARIO TAXISTAS DE
                                TELDE</h3>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2 text-right ">
                            <p>Invoice #555555</p>
                            <span>May 6 ,2017</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mt-3 content">
                    <div class="row">
                        <div class="col-md-7 col-sm-7 col-xs-7">
                            <table id="tablePreview" class="table">
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
                        <div class="col-md-5 col-sm-5 col-xs-5">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center ">
                            
                                <table class=" table table-bordered table-price">
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
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            
                                <table class=" table table-bordered table-price">
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
                <div class="col-md-12 col-sm-12 col-xs-12 invoice  ">
                    <table border="0" cellspacing="0" cellpadding="0" class="table-responsive-sm">
                        <thead>
                            <tr>
                                <!-- <th>#</th>-->
                                <th class="text-left">DESCRIPTION</th>
                                <th class="text-right"> Cantidad Kwh</th>
                                <th class="text-right">Precio KWh €</th>

                            </tr>
                        </thead>
                        <tbody id="tbodyI">
                            <tr>
                                <!-- <td class="no">01</td> -->
                                <td class="text-left">
                                    P1_Punta (kWh)
                                </td>
                                <td class="unit">2458.56</td>
                                <td class="qty">245.02 €</td>

                            </tr>
                            <tr>
                                <!-- <td class="no">02</td> -->
                                <td class="text-left">
                                    P2_Punta (kWh)
                                </td>
                                <td class="unit">2621.12</td>
                                <td class="qty">232.43 €</td>
                            </tr>
                            <tr>
                                <!-- <td class="no">03</td> -->
                                <td class="text-left">
                                    P3_Valle (kWh)
                                </td>
                                <td class="unit">2.35</td>
                                <td class="qty">0.15 €</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>TOTAL</td>
                                <td>5082.03</td>
                                <td>477.60 €</td>
                            </tr>
                            <tr>
                                <td colspan="1">TOTAL A PAGAR</td>
                                <td>5082.03</td>
                                <td>477.60 €</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-right col-md-12 col-sm-12">
                        <button id="printInvoice" class="btn btn-info btnGenerate"><i class="fa fa-print"></i>
                            Print</button>
                        <button class="btn btn-info btnGenerate"><i class="fa fa-file-pdf-o"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-2">
    <div class="fade-in">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"><strong>Otros Conceptos</strong> <small>Form</small></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">Description</label>
                                    <input class="form-control" id="description" type="text" placeholder="Enter Description for concept">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="ccnumber">Precio</label>
                                    <input class="form-control" id="precio" type="number"
                                        placeholder="0000 0000">
                                </div>
                            </div>

                            <div class="col-sm-4 d-flex justify-content-center align-items-center">
                                <div class="form-group pt-4 ">
                                    <button id="btnAdd" class="btn btn-primary btnGenerate"> Agregar</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
</div>
@endsection