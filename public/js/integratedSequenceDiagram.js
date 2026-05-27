console.info('We are in integratedSequenceDiagram.js, ready to start');

var response;
var cantPacket = 0;
var current_packet = 1;
var pivotTable;
var pagination;
let params;
var protocols = {
    protocolName: "all",
    currentPacket: 1,
    cantPackets: 0,
    propertyId: "",
    response: {},
    table: {},
    htmlTable: {},
    diagram: {},
}

var pcapData;

var currentProtocolSlide = 0;


var navigationAlreadyAdded = false;

var spinnerTemplate = "<div class=\"text-center\" id=\"spinner-${protocol}\" > "
    + "<div class=\"spinner-border\" role=\"status\"> "
    + "<span class=\"sr-only\">Loading...</span> "
    + "</div>"
    + "</div>";

var diagramTemplate = "<div id=\"diagram-content-all\" class=\"col-sm-12 card diagram-content\"> "
    + "<div class=\"diagram\" id=\"diagram-all\" style=\"width: 100%; height: 800px; overflow-x: auto; overflow-y: auto; background: #FFFFFF; background-color: #FFFFFF; margin-top:2%;\">"
    + "</div> "

    + "<div class=\"mb-3\" id=\"pivot-table-all\" style=\"width:100%; border-collapse: collapse; border: 1px solid #F3F3F3; overflow: auto; margin-top:8px;\">"
    + "<table class='display' id='select-all' style='width:100%'>"
    + "</table>"
    + "</div>"

    + "<form class=\"my-2\">"
    + "<div class=\"row d-flex align-items-center\">" // Flexbox to align items horizontally and vertically in the same row
    + "<div class=\"col-auto\">" // Column for the dropdown button, adjusts its size automatically
    + "<div class=\"btn-group\">"
    + "<button type=\"button\" id=\"download-pcap-all\" class=\"btn btn-dark-red dropdown-toggle btn-block\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Download PCAP</button>"
    + "<div class=\"dropdown-menu\">"
    + "<a class=\"dropdown-item dropdown-color\" href=\"#\" onclick=\"buildPcapAllProtocols(this)\">Download PCAP file for current page</a>"
    + "<a class=\"dropdown-item dropdown-color\" href=\"#\" onclick=\"$('#downloadByParts').modal('show');\">Download specific PCAP file page</a>"
    + "<a class=\"dropdown-item dropdown-color\" href=\"#\" onclick=\"getPcapPart(true)\">Download Full PCAP file</a>"
    + "</div>" 
    + "</div>" 
    + "</div>" 
    + "<div class=\"col-auto ml-auto\">" 
    + "<div class=\"button-group d-flex\">" 
    + "<button id=\"prevPageD\" class=\"btn btn-secondary btn-integrated\" type=\"button\" onClick=\"prevPage()\">"
    + "<img src=\"/assets/icons/awesome-arrow-circle-down-left.svg\" alt=\"Previous\" class=\"arrow-integrated\" />"
    + "</button>"
    + "<button id=\"nextPageD\" class=\"btn btn-secondary btn-integrated ml-2\" type=\"button\" onClick=\"nextPage()\">"
    + "<img src=\"/assets/icons/awesome-arrow-circle-down-right.svg\" alt=\"Next\" class=\"arrow-integrated\" />"
    + "</button>"
    + "</div>" 
    + "</div>" 
    + "</div>" /
    + "</form>";




$('#selectAllToggle').click(function(event) {   
    if(this.checked) {
        $('#selectAllToggleLabel').text('Deselect all');
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $('#selectAllToggleLabel').text('Select all');    
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
}); 



$("div#resultsWrapper").on("click", "g.signal", function (e) {
    var indexOfSignal = $("#diagram-all" + " g.signal").index(this);
    $("#right").removeClass('hide');
    $("#left").removeClass('hide');
    $('#exampleModalCenter').modal('show');
    currentPacket = indexOfSignal;
    $("#exampleModalCenter .modal-body table").html(protocols.table[currentPacket]);
    if (protocols.currentPacket == protocols.cantPacket - 1) $("#right").addClass('hide');
    if (protocols.currentPacket == 0) $("#left").addClass('hide');
    // selectedProtocols[currentProtocolSlide].currentPacket == selectedProtocols[currentProtocolSlide].cantPackets
});

$("#close-modal").on("click", function () {
    $('#exampleModalCenter').modal('hide');
});
$(function() {
    $("#datepickerIntegratedStart").datetimepicker({
        format: "DD/MM/YYYY HH:mm:ss",
        defaultDate: moment().startOf("date").format("YYYY-MM-DD HH:mm")
    });
    $("#datepickerIntegratedEnd").datetimepicker({
        format: "DD/MM/YYYY HH:mm:ss",
        defaultDate: moment().endOf("date").format("YYYY-MM-DD HH:mm")
    });

});


function nextInfoPacket() {
    protocols.currentPacket = protocols.currentPacket + 1;
    if (protocols.currentPacket == protocols.cantPacket - 1) {
        $("#right").addClass('hide');
        $("#left").removeClass('hide');
    } else {
        $("#right").removeClass('hide');
        $("#left").removeClass('hide');
    }

    $("#exampleModalCenter .modal-body table").html(protocols.table[protocols.currentPacket]);
}

function previousInfoPacket() {
    protocols.currentPacket = protocols.currentPacket - 1;
    if (protocols.currentPacket == 0) {
        $("#left").addClass('hide');
        $("#right").removeClass('hide');
    } else {
        $("#right").removeClass('hide');
        $("#left").removeClass('hide');
    }

    $("#exampleModalCenter .modal-body table").html(protocols.table[protocols.currentPacket]);
}

function enableGrabScrolling() {
    let viewport = document.querySelector('#pivot-table-all');
    let content = viewport.querySelector('#select-all');

    $(content).DataTable( {
        colReorder: true,
        stateSave:  true,
        scrollY: '350px',
        sScrollX: "100%",
        paging: false,
        scrollCollapse: true,
        bInfo : false,
    } );

    $(content).on('click', 'tr', function(e) {
        let checkbox = $(this).find(".generate_pcap");
        let isChecked = $(checkbox).is(':checked');

        console.log(isChecked, !isChecked);
        $(checkbox).prop("checked", !isChecked);
    });

    enableDiagramScrolling(protocolId);
}

function enableDiagramScrolling(protocolId) {
    let viewport = document.querySelector('#diagram-' + protocolId);
    let content = viewport.querySelector('.sequence');
    let scr = new ScrollBooster({
        viewport: viewport,
        content: content,
        onUpdate: (data) => {
            // viewport.scrollLeft = data.position.x
            // viewport.scrollTop = data.position.y
            content.style.transform = `translate(
            ${-data.position.x}px,
            ${-data.position.y}px
        )`
        }
    })
}

function buildParameterList(){
    
    let parameters = {
        startDate: moment(
            document.getElementById("start_date").value,
            "DD/MM/YYYY HH:mm:ss a"
        ).format("YYYY-MM-DDTHH:mm:ss"),
        endDate: moment(
            document.getElementById("end_date").value,
            "DD/MM/YYYY HH:mm:ss a"
        ).format("YYYY-MM-DDTHH:mm:ss"),
        imsi: document.getElementById("imsi").value,
        msisdn: document.getElementById("msisdn").value,
        page: "1",
        filterLogic: "AND"
    };

    if (parameters.startDate == "") {
        toastr.error('Please add an start date');
        document.getElementById('start_date').focus();
        return null;
    }

    if (parameters.endDate == "") {
        toastr.error('Please add an end date');
        document.getElementById('end_date').focus();
        return null;
    }

    if (parameters.imsi == "") parameters.imsi = "NA";
    if (parameters.msisdn == "") parameters.msisdn = "NA";
    if (parameters.filterLogic == "") parameters.filterLogic = "AND";

    return parameters;
}

function updateDiagram() {
    startDate = moment(
        document.getElementById("start_date").value,
        "DD/MM/YYYY HH:mm:ss a"
    )
    endDate =  moment(
        document.getElementById("end_date").value,
        "DD/MM/YYYY HH:mm:ss a"
    )
    if (startDate > endDate) {
        toastr.error('End datetime should not be earlier than start datetime ');
        return ;
    }
    else {
        imsi = document.getElementById("imsi").value;
        msisdn = document.getElementById("msisdn").value;
            timeDiff = endDate.diff(startDate, 'minutes')
            if (timeLimitRequest >= timeDiff || ((imsi || imsi.trim()) || (msisdn && msisdn.trim()))) {
                $("#paginationIndicator").hide();
                $("#packetAmtIndicatorBtns").hide();
                params = buildParameterList();
            
                if(params == null)
                    return;
            
                $('#resultsWrapper').empty();
                currentProtocolSlide = 0;
                selectedProtocols = [];
                console.info("Building Diagram For All Protocols");
                requestAllProtocolDiagram(params.startDate, params.endDate, params.imsi, params.msisdn, params.filterLogic, params.page)
                $('.carousel').carousel();
                $('#diagram-wrapper').removeClass('hide');
                
                

            } else {
                toastr.error(`The date range must be under ${timeLimitRequest} minutes, please change it.`);
                document.getElementById('end_date').focus();
            }
    }
}

//TODO: This logic code can be re-used from existing
function getPcapPart(requestFullPcapFile) {

    let totalPcapPages = $('#totalPages').text(); 
    let pageNumber = requestFullPcapFile ? '1-' + totalPcapPages : $('#txtPartialPageNumber').val(); 
    const numberRangeRegex = /^\d+-\d+$/;

    if (!numberRangeRegex.test(pageNumber) && !(!isNaN(pageNumber) && pageNumber.trim() !== '')) {
        toastr.error('The provided input is not a valid number range [0 - 0]');
        $('#loadingModal').modal('hide');
        return;
    }

   
    
    startDate = moment(
    document.getElementById("start_date").value,
    "DD/MM/YYYY HH:mm:ss a"
    )

    endDate =  moment(
    document.getElementById("end_date").value,
    "DD/MM/YYYY HH:mm:ss a"
    )

    imsi = document.getElementById("imsi").value;
    msisdn = document.getElementById("msisdn").value;
    timeDiff = endDate.diff(startDate, 'minutes')
    if (timeLimitRequest >= timeDiff || ((imsi || imsi.trim()) || (msisdn && msisdn.trim()))) {

        params = buildParameterList();

        if(params == null)
            return;

        currentProtocolSlide = 0;
        selectedProtocols = [];
        console.info("Building Diagram For All Protocols");
        requestPcapByPart(params.startDate, params.endDate, params.imsi, params.msisdn, params.filterLogic, pageNumber)
        $('.carousel').carousel();
    } else {
        toastr.error(`The date range must be under ${timeLimitRequest} minutes, please change it.`);
        document.getElementById('end_date').focus();
    }
    
}

function addToSelectedList(protocolObject) {
    var index = selectedProtocols.findIndex(x => x.protocolName == protocolObject.protocolName);

    if (index < 0)
        selectedProtocols.push(protocolObject);
    else
        selectedProtocols[index] = protocolObject;
}

function requestAllProtocolDiagram(startDate, endDate, imsi, msisdn, filterLogic, page) {

    var itemClassNames = "carousel-item active";
    const timeZoneWeb = Intl.DateTimeFormat().resolvedOptions().timeZone;
    $("#packetAmtIndicator").text("Loading...");
    $("#packetAmtIndicator").show();
    if ($("#carousel-item-all").length < 1)
        $("#resultsWrapper").append($("<div/>", { class: itemClassNames, id: "carousel-item-all" }).html(spinnerTemplate));

    $.ajaxSetup({
        timeout: 200000,
        retryAfter: 5000
    });

    $.ajax({
        url: deploymentUrl + "/buildDiagramAll/",
        contentType: "application/json",
        type: "POST",
        data: JSON.stringify({
            startDate: startDate,
            endDate: endDate,
            imsi: imsi,
            msisdn: msisdn,
            page: page,
            filterLogic: filterLogic,
            timezone: timeZoneWeb,
            protocols: {
                DIAMETER: $("#protocol-diameter").is(":checked"),
                MAP: $("#protocol-ss7map").is(":checked"),
                HTTP: $("#protocol-http").is(":checked"),
                SMPP: $("#protocol-smpp").is(":checked"),
                GTP: $("#protocol-gtp").is(":checked"),
                SIP: $("#protocol-sip").is(":checked"),
                CAMEL:$("#protocol-ss7cap").is(":checked"),
                "HTTP-OCS": $("#protocol-http-ocs").is(":checked"),
                "HTTP-SS7": $("#protocol-http-ss7").is(":checked")
            }
        }),
        success: (data) => {
            console.log(data)
            if (data.result.length == 0) {
                console.info("NO DATA FOR ");
                $("#carousel-item-all").remove();
                $("#packetAmtIndicator").text("Empty response.");

                return;
            }
            $("#packetAmtIndicator").hide();
            let arr = [];
            pagination = {
                totalPages: data.totalPages,
                recordsPerPage: data.recordsPerPage,
                currentPage: data.currentPage
            }
            if (pagination.totalPages >= 1 && pagination.currentPage < pagination.totalPages) {
                $("#netxtPage").attr("disabled", false);
            }
            if (pagination.currentPage === 1) {
                $("#prevPage").attr("disabled", true);
            }
            pcapData =  data.pcapData;
            const resultData = data.result;
            $("#totalPages").text(data.totalPages);
            $("#partialTotalPages").text('Enter page number range, allowed pattern:  minPage-maxPage  or a single page number, (current max page is ' +  data.totalPages + ' )');
            $("#recordsPerPage").text(data.recordsPerPage);
            $("#currentPage").text(data.currentPage);
            $("#paginationIndicator").show();
            $("#packetAmtIndicatorBtns").show();
            
            for (const key in resultData) {
                if (Object.hasOwnProperty.call(resultData, key)) {
                    let element = resultData[key];
                    element = element.replaceAll(/((\d{4})-(\d\d)-(\d\d)T(\d\d):(\d\d):(\d\d).(\d\d\d)Z)/g, (str, p1, p2, offset, s) => {
                        return p1.replace('T', ' ').replace('Z', '');
                    });
                    arr.push(element);
                }
            }
            console.log(diagramTemplate)
            $("#DiagramsDrawingBoard").append($(diagramTemplate));
            protocols.response = arr;
            protocols.diagram = arr.shift();
            protocols.table = arr;
            protocols.cantPacket = arr.length;
            protocols.currentPacket = 0;
            diagramAll = protocols.diagram

            try {
                var svgId = "diagram-all";
                var diagram = Diagram.parse(diagramAll);
                diagram.drawSVG(svgId, { theme: 'simple' });
            } catch (e) {
                console.warn("Cannot build diagram all due to: ", e)
            }
            var element = $('#diagram-content-all').detach();
            $("#carousel-item-all").html(element);

            setTimeout(() => {
                $('#resultsWrapper div:nth-child(1)').siblings().removeClass('active');
                //enableGrabScrolling();
                $("#download-pcap-all").prop(
                    "disabled",
                    false
                );
            }, 1000);

            setTimeout(() => {
                let paddingRigth = 65;
                $("#diagram-all g.signal").attr("transform", `translate(${paddingRigth}, -50)`);
                $("#diagram-all svg.sequence").children("line").attr("transform", `translate(${paddingRigth}, 0)`);
                let widthDiagram = $("#diagram-all svg.sequence").width();
                $("#diagram-all svg.sequence").attr("width", `${widthDiagram + paddingRigth}`);
                $("#diagram-all g.actor").children().attr("transform", `translate(${paddingRigth}, 0)`);

                let lastScrollTop = 0;
                $("#diagram-all").scroll(() => {
                    let currentScrollTop = $("#diagram-all").scrollTop();
                    let scrollAmount = currentScrollTop - lastScrollTop;
                    lastScrollTop = $("#diagram-all").scrollTop();
                    let length = $("#diagram-all" + " g.actor").length;
                    for (var i = 0; i < length; i++) {
                        if (i % 2 == 0) {
                            let actor = $("#diagram-all" + " g.actor").eq(i);
                            let currentRectY = parseFloat(actor.children("rect").attr("y"));
                            let currentTextY = parseFloat(actor.children("text").attr("y"));

                            currentRectY = currentRectY + scrollAmount;
                            actor.children("rect").attr("y", `${currentRectY}`);
                            
                            currentTextY = currentTextY + scrollAmount;
                            actor.children("text").attr("y", `${currentTextY}`);
                        }
                    }
                });
                $("#diagram-all g.signal").each(function()
                {
                    option = $(this).text().split("_")[0]
                    switch (option) {
                        case "smpp" :
                            $(this)
                                .children("text")
                                .css({ fill: "#2eb85c" });
                            break;

                        case "diameter":
                            $(this)
                                .children("text")
                                .css({ fill: "#F49D1A" });
                            break;

                        case "gtp":
                            $(this)
                                .children("text")
                                .css({ fill: "#e55353" });
                            break;

                        case "http":
                            $(this)
                                .children("text")
                                .css({ fill: "#B01E68" });
                            break;

                        case "sip":
                            $(this)
                                .children("text")
                                .css({ fill: "#344D67" });
                            break;

                        case "cap":
                            $(this)
                                .children("text")
                                .css({ fill: "#42855B" });
                            break;

                        case "map":
                            $(this)
                                .children("text")
                                .css({ fill: "#EB6440" });
                            break;
                        case "http-ocs":
                            $(this)
                                .children("text")
                                .css({ fill: "#1e84b0" });
                            break;

                        case "http-ss7":
                            $(this)
                                .children("text")
                                .css({ fill: "#651eb0" });
                            break;
                    }
                });
                $("#diagram-all g.note").each(function() {
                    const $note = $(this);
                    const $nextSignal = $note.next("g.signal");
                    let white = "#FFFFFF";
                
                    if ($nextSignal.length > 0) {
                        // define protocol type 
                        const option = $nextSignal.text().split("_")[0];
                        let color = "#d9d9d9"; // defauilt color
                
                        switch (option) {
                            case "smpp":
                                color = "#2eb85c";
                                break;
                            case "diameter":
                                color = "#F49D1A";
                                break;
                            case "gtp":
                                color = "#e55353";
                                break;
                            case "http":
                                color = "#B01E68";
                                break;
                            case "sip":
                                color = "#344D67";
                                break;
                            case "cap":
                                color = "#42855B";
                                break;
                            case "map":
                                color = "#EB6440";
                                break;
                            case "http-ocs":
                                color = "#1e84b0";
                                break;
                            case "http-ss7":
                                color = "#651eb0";
                                break;
                        }
                
                        // Cambiar el color de relleno del rectángulo dentro del note
                        $note.find("rect").attr("fill", color);
                        $note.find("text").attr("fill", white);
                    }
                });

                
                
                




            }, 100);
        },
        error: (data) => {
            //$("#carousel-item-"+protocolName).remove();
            console.warn('Ajax request failed... Will try soon...');
            console.debug(data)
            // if ($("#carousel-item-" + protocolName).length < 1)
            // $("#resultsWrapper").html('<h4 style="color:red">Sorry, an error has ocurred, please try again or contact the administrator</h4>');
            //setTimeout(() => { requestProtocolDiagram(protocolObject, startDate, endDate, imsi, msisdn, endtoend_id, ip_src, ip_dst, smpp_source_addr, smpp_destination_addr, method, ip_from, ip_to, body, filterLogic) }, $.ajaxSetup().retryAfter);
        }
    });

}

function rangeToStringList(range) {
    const [start, end] = range.match(/\d+/g).map(Number);
    const strNumericList = [];
    let totalPcapPages = parseInt($('#totalPages').text()); 

    if (end < start || end > totalPcapPages)
        return '0';
    

    for (let i = start; i <= end; i++) {
      strNumericList.push(i);
    }
    return strNumericList.join(',');
  }

//TODO: This logic code can be re-used from existing
function requestPcapByPart(startDate, endDate, imsi, msisdn, filterLogic, page) {

    isNumber = (!isNaN(page) && page.trim() !== '');

    if (isNumber && page > parseInt($('#totalPages').text())){
        toastr.error('The provided input is out of range, max page allowed is ' + $('#totalPages').text());
        return;
    }

    const numericRange = "[" + page + "]";
    stringRange = rangeToStringList(numericRange);

    if (stringRange == '0'){
        toastr.error('The end page number cannot be greater than the start page number');
        return;
    }

    const numberList = isNumber ? page : rangeToStringList(numericRange);

    const timeZoneWeb = Intl.DateTimeFormat().resolvedOptions().timeZone;


    $('#loadingModal').modal('show');
    $.ajaxSetup({
        timeout: 200000,
        retryAfter: 5000
    });

    $.ajax({
        url: deploymentUrl + "/v2/add/parts",
        contentType: "application/json",
        type: "POST",
        data: JSON.stringify({
            startDate: startDate,
            endDate: endDate,
            imsi: imsi,
            msisdn: msisdn,
            page: numberList,
            filterLogic: filterLogic,
            timezone: timeZoneWeb,
            protocols: {
                DIAMETER: $("#protocol-diameter").is(":checked"),
                MAP: $("#protocol-ss7map").is(":checked"),
                HTTP: $("#protocol-http").is(":checked"),
                SMPP: $("#protocol-smpp").is(":checked"),
                GTP: $("#protocol-gtp").is(":checked"),
                SIP: $("#protocol-sip").is(":checked"),
                CAMEL:$("#protocol-ss7cap").is(":checked"),
                "HTTP-OCS": $("#protocol-http-ocs").is(":checked"),
                "HTTP-SS7": $("#protocol-http-ss7").is(":checked")
            }
        }),
        success: (data) => {
            // window.location.href = deploymentUrl + '/download/'+data.Result;
            $('#downloadByParts').modal('hide');

            // $('#loadingModal').modal('hide');
            hideLoadingModal();
            

            redirectToDownloadDashboard();
            return;
        },
        error: (data) => {
            console.debug(data)
            hideLoadingModal();
            console.warn('Ajax request failed... Will try soon...');
            console.debug(data)
        }
    });

}

function nextPage() {
    page = pagination.currentPage + 1;
    if (page <= pagination.totalPages) {
        if (page === pagination.totalPages) {
            $('#netxtPage').attr('disabled', true);
        }
        pagination.currentPage = page
        $("#carousel-item-all").remove();
        requestAllProtocolDiagram(params.startDate, params.endDate, params.imsi, params.msisdn, params.filterLogic, String(page))
        $('#prevPage').attr('disabled', false);
        console.log(pagination)
    }
}

function prevPage() {
    page = pagination.currentPage - 1;
    if (page >= 1) {
        if (page === 1) {
            $('#prevPage').attr('disabled', true);
        }
        pagination.currentPage = page
        $("#carousel-item-all").remove();
        requestAllProtocolDiagram(params.startDate, params.endDate, params.imsi, params.msisdn, params.filterLogic, String(page))
        $('#netxtPage').attr('disabled', false);
        console.log(pagination)
    }
}


function slideNextProtocol() {
    var nextButtons = document.getElementsByClassName("btnNext");
    // Disable nav button to not allow multiple clicking
    for (var i = 0; i < nextButtons.length; i++) {
        nextButtons[i].disabled = true;
    }
    
    currentProtocolSlide++;
    
    
    $("#carouselMergedDiagrams").carousel('next');
    console.info("Protocol index: " + currentProtocolSlide);

    if (currentProtocolSlide >= selectedProtocols.length)
        currentProtocolSlide = 0;

    console.info("Protocol index update: " + currentProtocolSlide);
    console.info("protocol on screen " + selectedProtocols[currentProtocolSlide].protocolName);
    // Delay required because of the async function of carousel event, in order to enable / disable
    // nav buttons.
    setTimeout(function() {
        for (var i = 0; i < nextButtons.length; i++) {
            nextButtons[i].disabled = false;
        }
    }, 500);

    updateOnSlide(selectedProtocols[currentProtocolSlide].protocolId);
}

function slidePrevProtocol() {
    var prevButtons = document.getElementsByClassName("btnPrevious");
    // Disable nav button to not allow multiple clicking
    for (var i = 0; i < prevButtons.length; i++) {
        prevButtons[i].disabled = true;
    }
    
    currentProtocolSlide--;

    $("#carouselMergedDiagrams").carousel('prev');
    console.log("Protocol index: " + currentProtocolSlide);

    if (currentProtocolSlide < 0)
        currentProtocolSlide = selectedProtocols.length - 1;

    console.log("Protocol index update: " + currentProtocolSlide);
    console.log("protocol on screen " + selectedProtocols[currentProtocolSlide].protocolName);
    // Delay required because of the async function of carousel event, in order to enable / disable
    // nav buttons.
    setTimeout(function() {
        for (var i = 0; i < prevButtons.length; i++) {
            prevButtons[i].disabled = false;
        }
    }, 500);

    updateOnSlide(selectedProtocols[currentProtocolSlide].protocolId);
}

function updateOnSlide(protocolId){
    let viewport = document.querySelector('#pivot-table-' + protocolId)
    let content = viewport.querySelector('#select-' + protocolId)

    $(content).DataTable().columns.adjust();
    $(content).width($(viewport).width());
}

function buildPcapAllProtocols() {
    let params = buildParameterList();
    let jsonRequest = {};
    let fileIndexArray = [];

    if(params == null)
        return;

    $('#loadingModal').modal('show');
    
    for (const key in selectedProtocols) {
        if (Object.hasOwnProperty.call(selectedProtocols, key)) {
            const element = selectedProtocols[key];
            let rowSelect = $("#pivot-table-" + element.protocolId + " table tbody tr > td").parent();
            if (rowSelect.length > 0) {
                for (let i = 0; i < rowSelect.length; i++) {
                    fileIndexArray.push({
                        file_index : rowSelect[i].querySelector(".file_index").innerText,
                        frame_number : rowSelect[i].querySelector(".frame_number").innerText,
                        protocol_name : element.protocolName
                    });
                }
            }
        }
    }

    jsonRequest = {
        payload: fileIndexArray,
        parameters: params,
        frames: pcapData
    }

    $.ajax({
        url: deploymentUrl + "/v2/add",
        contentType: "application/json",
        type: 'POST',
        data: JSON.stringify(jsonRequest),
        timeout: 300000,

        success: function (data) {
            // $('#loadingModal').modal('hide');
            hideLoadingModal();

            redirectToDownloadDashboard();
        },
        error: function (data) {
            toastr.error('Could not extract PCAP file.');
            // hideLoadingModal();
            setTimeout(function () { $('#loadingModal').modal('hide'); }, 500);
        },
    });

}

window.addEventListener('load', () => {
    
    // Select all elements with the class 'c-sidebar-nav-link'
    const sidebarLinks = document.querySelectorAll('.c-sidebar-nav-link');

    // Check if there are any links in the sidebar
    if (sidebarLinks.length > 0) {
 
        // Determine if any of the links already have the 'c-active' class
        const activeLink = Array.from(sidebarLinks).find(link => {
            return link.classList.contains('c-active');
        });

        // If no link is active, add the 'c-active' class to the first sidebar link
        if (!activeLink) {
            sidebarLinks[0].classList.add('c-active');
        } 
    }
});

document.addEventListener("DOMContentLoaded", function() {
    

    // Prevent dropdown from closing when clicking on the switch
    const selectAllSwitch = document.getElementById('select-all');
    selectAllSwitch.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent closing the dropdown
    });

    document.querySelectorAll('.dropdown-menu input, .dropdown-menu label').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.stopPropagation(); // Evita que el evento alcance al contenedor del dropdown
        });
    });

    document.getElementById('select-all').addEventListener('change', function () {
        const isChecked = this.checked;
        const checkboxes = document.querySelectorAll(this.dataset.target);

        // Cambiar el estado de los checkboxes
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = isChecked;
        });


        const label = document.querySelector('label[for="select-all"]');
        label.textContent = isChecked ? 'Deselect All' : 'Select All';
    });


});

document.addEventListener('click', function (event) {
    if (event.target.closest('#prevPageD')) {
        window.prevPage();
    }
    if (event.target.closest('#nextPageD')) {
        window.nextPage();
    }
});
let downloadTab = null;
function redirectToDownloadDashboard() {
    toastr.success('PCAP added to download queue!');
    // window.open('/download', '_blank');
    // const newTab = window.open('/download', '_blank');

    // Attempt to blur the new tab immediately after opening
    // if (newTab) {
        // newTab.blur(); 
        // window.focus();
    // }

}

function hideLoadingModal() {
    $('#loadingModal').modal('hide');
    
    setTimeout(() => {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $('#loadingModal').hide();
    }, 300);
}



