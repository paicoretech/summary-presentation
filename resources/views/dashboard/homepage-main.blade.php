@extends('dashboard.base')

@section('content')

    <div id="DiagramsDrawingBoard" class=".d-none">
      
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h3>Message Information</h3>
                </div>
                <div class="modal-body" style="padding: 4px">
                  <div style="overflow: auto">
                    <table class="table"></table>
                  </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group" aria-label="..." style="margin:0 auto">

                        <button type="button" class="btn btn-default" id="left" onclick="previousInfoPacket()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                            </svg>
                        </button>

                        <button type="button" class="btn btn-default" id="right" onclick="nextInfoPacket()">
                            <svg  width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                            </svg>
                        </button>
                    </div>
                    <button id="close-modal" type="button" class="btn btn-secondary" >Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body text-center">
                    <div class="spinner-border" style="width: 3.5rem; height: 3.5rem; text-align: center" role="status">

                    </div>
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid filter-section" id="#main-dashboard">
      <div class="fade-in">

        <!-- Filters -->
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header"><strong>Filters</strong></div>

              <div class="card-body">
                <div class="container">
                  <div class="row justify-content-start">
                      <div class='form-group col-4'> 
                            <label for="date_start">{{__('*Start date')}}</label>
                            <div class="input-group date" id="datepickerStart" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#datepickerStart" id="start_date"/>
                                <div class="input-group-append" data-target="#datepickerStart" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                      </div>
                      <div class='form-group col-4'> 
                            <label for="date_start">{{__('*End date')}}</label>
                            <div class="input-group date" id="datepickerEnd" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#datepickerEnd" id="end_date"/>
                                <div class="input-group-append" data-target="#datepickerEnd" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                          <label class="form-label">Source (IP/OPC)</label>
                          <input type="text" class="form-control" placeholder="IP Source" aria-label="Server" id="ip_src" step="2">
                      </div>
                      <div class="col-4">
                          <label class="form-label">Destination (IP/DPC)</label>
                          <input type="text" class="form-control" placeholder="IP Destination" aria-label="Server" id="ip_dst" step="2">
                      </div>

                  </div>
                  <div class="row justify-content-start my-4">
                      <!--div class="col-3 my-4">
                          <label class="form-label">Filter Logic</label>
                          <select class="form-select" aria-label="Filter logic" id="filter_logic">
                              <option value="AND">AND</option>
                              <option value="OR">OR</option>
                          </select>
                      </div-->
                      <div class="col-3">
                          <label class="form-label">IMSI</label>
                          <input type="number" class="form-control" placeholder="IMSI" aria-label="Server" id="imsi" step="2">
                      </div>
                      <div class="col-3">
                          <label class="form-label">Correlation ID</label>
                          <div  class="d-inline-block" tabindex="0" data-container="body"  data-toggle="popover" data-trigger="focus"  title="Details: " data-html="true" data-content="Diameter-> End to End Id  <br /> SIP -> call-Id <br /> SMPP -> sequence number " >
                            <svg class="questionMark" style="margin-bottom: 0.3rem;cursor: pointer;"
                              width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                              <path
                                d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                            </svg>
                          </div>
                          <input type="text" class="form-control" placeholder="Correlation ID" aria-label="Server" id="endtoend_id" step="2">
                      </div>
                      <div class="col-3">
                          <label class="form-label">MSISDN</label>
                          <input type="number" class="form-control" placeholder="MSISDN" aria-label="Server" id="msisdn" step="2">
                      </div>
                    </div>
                    <div class="row justify-content-start my-4">
                      <div class="col-3">
                          <label class="form-label">Source Number</label>
                          <div class="d-inline-block" tabindex="0" data-container="body" data-toggle="popover" data-trigger="focus"
                            title="Details: " data-html="true"
                            data-content="SMPP -> SOURCE ADDR <br /> SIP -> ORIGIN <br /> SS7MAP -> gsm sms tp-oa <br /> SS7CAP -> camel calling Party Number">
                            <svg class="questionMark" style="margin-bottom: 0.3rem;cursor: pointer;" width="16" height="16" fill="currentColor"
                              class="bi bi-question-circle" viewBox="0 0 16 16">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                              <path
                                d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                            </svg>
                          </div>
                          <input type="number" class="form-control" placeholder="Source Number" aria-label="Server" id="smpp-source_addr" step="2">
                      </div>
                      <div class="col-3">
                          <label class="form-label"> Destination Number</label>
                          <div class="d-inline-block" tabindex="0" data-container="body" data-toggle="popover" data-trigger="focus"
                            title="Details: " data-html="true" data-content="SMPP -> DESTINATION ADDR <br/> SIP -> DESTINATION <br /> SS7MAP -> e164 msisdn <br /> SS7CAP -> gsm_a dtap cld_party_bcd_num ">
                            <svg class="questionMark" style="margin-bottom: 0.3rem;cursor: pointer;" width="16" height="16" fill="currentColor"
                              class="bi bi-question-circle" viewBox="0 0 16 16">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                              <path
                                d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                            </svg>
                          </div>
                          <input type="number" class="form-control" placeholder=" Destination Number" aria-label="Server" id="smpp-destination_addr" step="2">
                      </div>
                    </div>

                    <fieldset class="my-4">
                      <legend>HTTP FILTERS</legend>
                      <div class="row">
                        <div class="col-3">
                          <label class="form-label">METHOD</label>
                          <select class="form-control" aria-label="Method" id="filter_method">
                            <option value="">ALL</option>
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                          </select>
                        </div>
                        <div class="col-3">
                          <label class="form-label">BODY</label>
                          <input type="text" class="form-control" placeholder="BODY" aria-label="Server" id="body">
                        </div>
                      </div>
                    </fieldset>
                  </div>  
                </div>


                <div class="card-footer">
                  <button id="apply" class="btn btn-secondary float-end" type="button" onClick="updateDiagram()">Search</button>
                </div>
            </div>
          </div>
        </div>

        <!-- Protocols selector -->
        <div class="row">
          <div class="col-sm-6 col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-muted text-right mb-4">
                  <svg class="c-icon c-icon-2xl">
                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-location-pin"></use>
                  </svg>
                </div>
                <div class="text-value-lg">Diameter</div>
                <span class="badge badge-secondary hide" id="packetAmtIndicator-diameter">Loading...</span>
                <small class="text-muted text-uppercase font-weight-bold">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input protocol-selector-checkbox" id="protocol-diameter" type="checkbox" value="diameter" checked>
                    <label class="form-check-label" for="protocol-diameter">Display</label>
                  </div>
                </small>
              </div>
            </div>
          </div>
          <!-- /.col-->
          <div class="col-sm-6 col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-muted text-right mb-4">
                  <svg class="c-icon c-icon-2xl">
                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-phone"></use>
                  </svg>
                </div>
                <div class="text-value-lg">SIP</div>
                <span class="badge badge-secondary hide" id="packetAmtIndicator-sip">Loading...</span>
                <small class="text-muted text-uppercase font-weight-bold">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input protocol-selector-checkbox" id="protocol-sip" type="checkbox" value="sip" checked>
                    <label class="form-check-label" for="protocol-sip">Display</label>
                  </div>
                </small>
              </div>
            </div>
          </div>
          <!-- /.col-->
          <div class="col-sm-6 col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-muted text-right mb-4">
                  <svg class="c-icon c-icon-2xl">
                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-tag"></use>
                  </svg>
                </div>
                <div class="text-value-lg">SS7 MAP</div>
                <span class="badge badge-secondary hide" id="packetAmtIndicator-ss7map">Loading...</span>
                <small class="text-muted text-uppercase font-weight-bold">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input protocol-selector-checkbox" id="protocol-ss7map" type="checkbox" value="ss7map" checked>
                    <label class="form-check-label" for="protocol-ss7map">Display</label>
                  </div>
                </small>
              </div>
            </div>
          </div>
          <!-- /.col-->
          <div class="col-sm-6 col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-muted text-right mb-4">
                  <svg class="c-icon c-icon-2xl">
                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-chart-pie"></use>
                  </svg>
                </div>
                <div class="text-value-lg">SS7 CAP</div>
                <span class="badge badge-secondary hide" id="packetAmtIndicator-ss7cap">Loading...</span>
                <small class="text-muted text-uppercase font-weight-bold">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input protocol-selector-checkbox" id="protocol-ss7cap" type="checkbox" value="ss7cap" checked>
                    <label class="form-check-label" for="protocol-ss7cap">Display</label>
                  </div>
                </small>
              </div>
            </div>
          </div>
          <!-- /.col-->
          <div class="col-sm-6 col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-muted text-right mb-4">
                  <svg class="c-icon c-icon-2xl">
                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-speedometer"></use>
                  </svg>
                </div>
                <div class="text-value-lg">GTP</div>
                <span class="badge badge-secondary hide" id="packetAmtIndicator-gtp">Loading...</span>
                <small class="text-muted text-uppercase font-weight-bold">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input protocol-selector-checkbox" id="protocol-gtp" type="checkbox" value="gtp" checked>
                    <label class="form-check-label" for="protocol-gtp">Display</label>
                  </div>
                </small>

              </div>
            </div>
          </div>
          <!-- /.col-->
          <div class="col-sm-6 col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-muted text-right mb-4">
                  <svg class="c-icon c-icon-2xl">
                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-speech"></use>
                  </svg>
                </div>
                <div class="text-value-lg">SMPP</div>
                <span class="badge badge-secondary hide" id="packetAmtIndicator-smpp">Loading...</span>
                <small class="text-muted text-uppercase font-weight-bold">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input protocol-selector-checkbox" id="protocol-smpp" type="checkbox" value="smpp" checked>
                    <label class="form-check-label" for="protocol-smpp">Display</label>
                  </div>
                </small>

              </div>
            </div>
          </div>
          <!-- /.col-->
          <div class="col-sm-6 col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-muted text-right mb-4">
                  <svg class="c-icon c-icon-2xl">
                    <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-globe-alt"></use>
                  </svg>
                </div>
                <div class="text-value-lg">HTTP</div>
                <span class="badge badge-secondary hide" id="packetAmtIndicator-http">Loading...</span>
                <small class="text-muted text-uppercase font-weight-bold">
                  <div class="form-check form-check-inline mr-1">
                    <input class="form-check-input protocol-selector-checkbox" id="protocol-http" type="checkbox" value="http" checked>
                    <label class="form-check-label" for="protocol-http">Display</label>
                  </div>
                </small>
              </div>
            </div>
          </div>
          <!-- /.col-->
        </div>

        <!-- data visualization -->
        <div class="row" id="diagram-wrapper">

          <div class="col-sm-12">

            <div class="card">
              <div class="card-header"><strong>Results</strong></div>
              
              <div class="card-body">

                <div class="carousel slide" id="carouselMergedDiagrams" data-interval="false">

                  <div class="carousel-inner" id="resultsWrapper">
                    
                  </div>

                </div>
                <!-- / carousel !-->
                
              </div>

            </div>

          </div>
            
        </div>

      </div>
    </div>

@endsection

@section('css')
  <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/scroller.bootstrap4.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/colReorder.dataTables.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}" />

  <link href="{{ asset('css/sequenceDiagram.css') }}" rel="stylesheet">
  <link href="{{ asset('js/bower_components/js-sequence-diagrams/dist/sequence-diagram.css') }}" rel="stylesheet">

  <link href="{{ asset('css/jquery.floatingscroll.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}"  crossorigin="anonymous" />
  <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
@endsection

@section('javascript')

  <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/js/dataTables.scroller.js') }}"></script>
  <script src="{{ asset('assets/js/dataTables.colReorder.min.js') }}"></script>
  
  <script src="{{ asset('assets/js/scrollbooster.min.js') }}"></script>
  <!-- <script src="https://unpkg.com/floatthead"></script> -->
  <script src="{{ asset('assets/js/jquery.floatThead.min.js') }}"></script>
  

  <script src="{{ asset('js/jquery.floatingscroll.js') }}"></script>

  <script src="{{ asset('js/Chart.min.js') }}"></script>
  <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
  <script src="{{ asset('js/main.js') }}" defer></script>

  <script src="{{ asset('js/bower_components/bower-webfontloader/webfont.js') }}"></script>
  <script src="{{ asset('js/bower_components/snap.svg/dist/snap.svg-min.js') }}"></script>
  <script src="{{ asset('js/bower_components/underscore/underscore-min.js') }}"></script>
  <script src="{{ asset('js/bower_components/js-sequence-diagrams/dist/sequence-diagram.js') }}"></script>
  <script src="{{ asset('js/uuidv4.min.js') }}"></script>


  <script>
    var deploymentUrl = "{{ config('services.ANALYTICS_SERVICE_URL') }}";
  </script>

  <script src="{{ asset('js/sequenceDiagram.js') }}"></script>
  <script src="{{ asset('js/popovers.js') }}"></script>
  <script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"
    integrity="sha512-2JBCbWoMJPH+Uj7Wq5OLub8E5edWHlTM4ar/YJkZh3plwB2INhhOC3eDoqHm1Za/ZOSksrLlURLoyXVdfQXqwg=="
    crossorigin="anonymous"></script>

@endsection
