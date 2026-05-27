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
                            <img src="{{ asset('assets/icons/awesome-arrow-circle-down-left.svg') }}" alt="Dropdown Arrow" class="arrow-integrated" />
                        </button>

                        <button type="button" class="btn btn-default" id="right" onclick="nextInfoPacket()">
                            <img src="{{ asset('assets/icons/awesome-arrow-circle-down-right.svg') }}" alt="Dropdown Arrow" class="arrow-integrated" />
                        </button>
                    </div>
                    <button id="close-modal" type="button" class="btn btn-dark-red" >Ok</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="downloadByParts" tabindex="-1" role="dialog" aria-labelledby="downloadByPartsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
               
                <div class="modal-header">
                  <h3>Download PCAP by page number</h3>
                </div>

                <div class="modal-body" style="padding: 4px">
                  <div style="overflow: auto">

                  <div class="container">
                    <div class="row">
                        <div class="col-sm-12" id="partialTotalPages"> </div> 
                      </div>

                      <div class="row">
                        <div class="col-sm-12">
                          <input type="text" class="form-control" placeholder="minPage-maxPage | or a single page number" aria-label="Server" id="txtPartialPageNumber" step="2">
                        </div>
                      </div>
                  </div>
                  </div>
                </div>


                <div class="modal-footer">
                    <div class="btn-group" role="group" aria-label="..." style="margin:0 auto">
                      <button id="download-part" type="button" class="btn btn-primary" onclick="getPcapPart(false)" >Download</button>
                      <button id="close-modal" type="button" class="btn btn-secondary" onclick="$('#downloadByParts').modal('hide');" >Close</button>
                    </div>
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
        <div class="row" id="filters">
          <div class="col-sm-12 card-filter">
            <div class="card" style="margin-bottom: 0rem">
              <div class="card-header card-header-filter d-flex justify-content-between align-items-center"><strong>@lang('filter.filters')</strong>
                <a href="{{ route('dashboard.download') }}" target="_blank">
                  <button id="btn-filters" class="btn btn-secondary btn-integrated" type="button">
                    <img src="{{ asset('assets/icons/download-icon.svg') }}" alt="Next" class="arrow-integrated" />
                  </button>
                </a>
              </div>

              <div class="card-body card-body-filter">
                <div class="">
                  <div class="row justify-content-start">
                      
                      <div class="form-group col-4">
                        <div class="input-group date datetime-picker" id="datepickerIntegratedStart" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#datepickerIntegratedStart" id="start_date"/>
                          <div class="input-group-append input-calendar" data-target="#datepickerIntegratedStart" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group col-4">
                        <div class="input-group date datetime-picker" id="datepickerIntegratedEnd" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#datepickerIntegratedEnd" id="end_date"/>
                          <div class="input-group-append input-calendar" data-target="#datepickerIntegratedEnd" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group col-4">
                        <x-multi-select-dropdown 
                        :objectsArray="$objectsArray" 
                        :selected="['diameter', 'sip','ss7map','ss7cap','gtp','http','http-ocs','http-ss7','smpp']" 
                        name="protocols"
                        />
                      </div>
                  </div>

                  <div class="row justify-content-start">
                      
                    <div class="col-4">
                      <div class="custom-input">
                          <input type="number" class="form-control" placeholder="IMSI" aria-label="Server" id="imsi" step="2">
                          <div class="arrows">
                              <img src="{{ asset('assets/icons/ionic-ios-arrow-up.svg') }}" alt="Arrow Up" class="arrow-up" onclick="increaseValue('imsi')">
                              <img src="{{ asset('assets/icons/ionic-ios-arrow-down.svg') }}" alt="Arrow Down" class="arrow-down" onclick="decreaseValue('imsi')">
                          </div>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="custom-input">
                          <input type="number" class="form-control" placeholder="MSISDN" aria-label="Server" id="msisdn" step="2">
                          <div class="arrows">
                              <img src="{{ asset('assets/icons/ionic-ios-arrow-up.svg') }}" alt="Arrow Up" class="arrow-up" onclick="increaseValue('msisdn')">
                              <img src="{{ asset('assets/icons/ionic-ios-arrow-down.svg') }}" alt="Arrow Down" class="arrow-down" onclick="decreaseValue('msisdn')">
                          </div>
                      </div>
                  </div>
                  <div class="col-4">
                    <div class="custom-input btn-search">
                      <button id="apply" class="btn btn-dark-red w-100 h-100" type="button" onClick="updateDiagram()">Search</button>
                    </div>
                </div>
                    
                    </div>
                  </div>
                </div>
            </div>
                  
          </div>
        </div>

        <!-- data visualization -->
        <div class="row hide" id="diagram-wrapper">

          <div class="col-sm-12 card-filter">

            <div class="card card-integrated-diagram">

                    <div class="card-body">
                      <div class="text-value-lg text-integrated-diagram">Integrated Diagram</div>
                      <div></div>
                      <span class="badge badge-secondary hide" id="packetAmtIndicator">Loading...</span>
                      <div class="flex-container">
                        <div id="paginationIndicator" class="hide content-left">
                          <div>
                            <label class="form-label label-intregrated">Total Pages:</label>
                            <label class="form-label label-intregrated " id="totalPages">0</label>
                          </div>
                          <div>
                            <label class="form-label label-intregrated">Records Per Protocol:</label>
                            <label class="form-label label-intregrated" id="recordsPerPage">0</label>
                          </div>
                          <div>
                            <label class="form-label label-intregrated">Current Page:</label>
                            <label class="form-label label-intregrated" id="currentPage">0</label>
                          </div>
                        </div>
                        <div class="card-footer .content-right hide" id="packetAmtIndicatorBtns">
                          <div class="pagination-controls">
                            <label class="form-label label-intregrated">Change Page</label>
                            <div class="button-group">
                                <button id="prevPage" class="btn btn-secondary btn-integrated" type="button" onClick="prevPage()">
                                    <img src="{{ asset('assets/icons/awesome-arrow-circle-down-left.svg') }}" alt="Previous" class="arrow-integrated" />
                                </button>
                                <button id="nextPage" class="btn btn-secondary btn-integrated" type="button" onClick="nextPage()">
                                    <img src="{{ asset('assets/icons/awesome-arrow-circle-down-right.svg') }}" alt="Next" class="arrow-integrated" />
                                </button>
                            </div>
                        </div>
                        </div>
                      </div>

                    </div>

                  </div>

            <div class="card card-integrated-diagram">
              <div class="card-header text-integrated-diagram"><strong>Results</strong></div>

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
  <link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}" integrity="sha512-PMjWzHVtwxdq7m7GIxBot5vdxUY+5aKP9wpKtvnNBZrVv1srI8tU6xvFMzG8crLNcMj/8Xl/WWmo/oAP/40p1g==" crossorigin="anonymous" />
  <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
@endsection

@section('javascript')

  <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/js/dataTables.scroller.js') }}"></script>
  <script src="{{ asset('assets/js/dataTables.colReorder.min.js') }}"></script>

  <script src="{{ asset('assets/js/scrollbooster.min.js') }}"></script>
  <!--<script src="https://unpkg.com/floatthead"></script> -->
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
    var timeLimitRequest = "{{ config('services.TIME_LIMIT_REQUEST') }}";
  //   

    // Función para aumentar el valor
function increaseValue(id) {
    const input = document.getElementById(id);
    input.value = (parseFloat(input.value) || 0) + 1;
}

// Función para disminuir el valor
function decreaseValue(id) {
    const input = document.getElementById(id);

    input.value = (parseFloat(input.value) || 0) - 1;
  document.addEventListener("DOMContentLoaded", function() {
      const sidebarLinks = document.querySelectorAll('.c-sidebar-nav-link');
      if (sidebarLinks.length > 0) {
          sidebarLinks[0].classList.add('c-active');
      }
  });

}

  // 
  </script>

  <script src="{{ asset('js/integratedSequenceDiagram.js') }}"></script>
  <script src="{{ asset('js/popovers.js') }}"></script>
  <script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>

@endsection
