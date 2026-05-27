var response;
var cantPacket = 0;
var current_packet = 1;
var pivotTable;
// var deploymentUrl = "http://192.168.0.14:9999";
//var deploymentUrl = "https://analytics.ws-paicbd.com";
var selectedProtocols = [];
var protocols = [
    {
        protocolName: "diameter",
        currentPacket: 1,
        cantPackets: 0,
        propertyId: "",
        response: {},
        table: {},
        htmlTable: {},
        diagram: {},
    },
    {
        protocolName: "sip",
        currentPacket: 1,
        cantPackets: 0,
        propertyId: "",
        response: {},
        table: {},
        htmlTable: {},
        diagram: {},
    },
    {
        protocolName: "ss7map",
        currentPacket: 1,
        cantPackets: 0,
        propertyId: "",
        response: {},
        table: {},
        htmlTable: {},
        diagram: {},
    },
    {
        protocolName: "ss7cap",
        currentPacket: 1,
        cantPackets: 0,
        propertyId: "",
        response: {},
        table: {},
        htmlTable: {},
        diagram: {},
    },
    {
        protocolName: "gtp",
        currentPacket: 1,
        cantPackets: 0,
        propertyId: "",
        response: {},
        table: {},
        htmlTable: {},
        diagram: {},
    },
    {
        protocolName: "smpp",
        currentPacket: 1,
        cantPackets: 0,
        propertyId: "",
        response: {},
        table: {},
        htmlTable: {},
        diagram: {},
    },
    {
        protocolName: "http",
        currentPacket: 1,
        cantPackets: 0,
        propertyId: "",
        response: {},
        table: {},
        htmlTable: {},
        diagram: {},
    }
];

var currentProtocolSlide = 0;

var navigationAlreadyAdded = false;

var buttonsTemplate = "<button class=\"btnPrevious btn btn-primary mx-1\" type=\"submit\" onclick=\"slidePrevProtocol()\" >&laquo; Previous</button>"
    + "<button class=\"btnNext btn btn-primary\" type=\"submit\" onclick=\"slideNextProtocol()\" >Next &raquo</button>";

var spinnerTemplate = "<div class=\"text-center\" id=\"spinner-${protocol}\" > "
    + "<div class=\"spinner-border\" role=\"status\"> "
    + "<span class=\"sr-only\">Loading...</span> "
    + "</div>"
    + "</div>";

var diagramTemplate = "<div id=\"diagram-content-${protocol}\" class=\"col-sm-12 card diagram-content\"> "
    + "<div class=\"diagram\" id=\"diagram-${protocol}\" style=\"width: 100%; height: 800px; overflow-x: auto; overflow-y: auto; background: #F3F3F3; margin-top:2%;\">"
    + "</div> "

    + "<div class=\"mb-3\" id=\"pivot-table-${protocol}\" style=\"width:100%; border-collapse: collapse; border: 1px solid #F3F3F3; overflow: auto; margin-top:8px;\">"
    + "<table class='display' id='select-${protocol}' style='width:100%'>"
    + "</table>"
    + "</div>"

    + "<form class=\"my-2\"> "
    + "<div class=\"row\">"
    + "<div class=\"col-4\">"
    + "<input id=\"download-pcap-all\" class=\"btn btn-primary btn-block\" value=\"Download PCAP All Protocols\" onclick=\"buildPcapAllProtocols(this)\"/>"
    + "</div>"
    + "<div class=\"col-4\">"
    + "<input id=\"download-pcap-${protocol}\" class=\"btn btn-secondary btn-block\" value=\"Download PCAP\" onclick=\"buildPcap(this)\" disabled/>"
    + "</div>"
    + "</div>"
    + "</form>"
    + "</div>";

$("div#resultsWrapper").on("click", "g.signal", function (e) {
    var indexOfSignal = $("#diagram-" + selectedProtocols[currentProtocolSlide].protocolId + " g.signal").index(this);
    $("#right").removeClass('hide');
    $("#left").removeClass('hide');
    $('#exampleModalCenter').modal('show');
    selectedProtocols[currentProtocolSlide].currentPacket = indexOfSignal;
    $("#exampleModalCenter .modal-body table").html(selectedProtocols[currentProtocolSlide].table[selectedProtocols[currentProtocolSlide].currentPacket]);
    if (selectedProtocols[currentProtocolSlide].currentPacket == selectedProtocols[currentProtocolSlide].cantPacket - 1) $("#right").addClass('hide');
    if (selectedProtocols[currentProtocolSlide].currentPacket == 0) $("#left").addClass('hide');
    // selectedProtocols[currentProtocolSlide].currentPacket == selectedProtocols[currentProtocolSlide].cantPackets
});

$("#close-modal").on("click", function () {
    $('#exampleModalCenter').modal('hide');
});
$(function() {
    $("#datepickerStart").datetimepicker({
        format: "DD/MM/YYYY HH:mm:ss",
        defaultDate: moment().startOf("date").format("YYYY-MM-DD HH:mm")
    });
    $("#datepickerEnd").datetimepicker({
        format: "DD/MM/YYYY HH:mm:ss",
        defaultDate: moment().endOf("date").format("YYYY-MM-DD HH:mm")
    });
});

function nextInfoPacket() {
    selectedProtocols[currentProtocolSlide].currentPacket = selectedProtocols[currentProtocolSlide].currentPacket + 1;
    if (selectedProtocols[currentProtocolSlide].currentPacket == selectedProtocols[currentProtocolSlide].cantPacket - 1) {
        $("#right").addClass('hide');
        $("#left").removeClass('hide');
    } else {
        $("#right").removeClass('hide');
        $("#left").removeClass('hide');
    }

    $("#exampleModalCenter .modal-body table").html(selectedProtocols[currentProtocolSlide].table[selectedProtocols[currentProtocolSlide].currentPacket]);
}

function previousInfoPacket() {
    selectedProtocols[currentProtocolSlide].currentPacket = selectedProtocols[currentProtocolSlide].currentPacket - 1;
    if (selectedProtocols[currentProtocolSlide].currentPacket == 0) {
        $("#left").addClass('hide');
        $("#right").removeClass('hide');
    } else {
        $("#right").removeClass('hide');
        $("#left").removeClass('hide');
    }

    $("#exampleModalCenter .modal-body table").html(selectedProtocols[currentProtocolSlide].table[selectedProtocols[currentProtocolSlide].currentPacket]);
}

function enableGrabScrolling(protocolId) {
    let viewport = document.querySelector('#pivot-table-' + protocolId);
    let content = viewport.querySelector('#select-' + protocolId);

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
/*
    $(content).floatThead({
        position: 'absolute',
        top: 50,
        responsiveContainer: function () { return $(".container-fluid") },
    });
*/
    //$(viewport).floatingScrollbar();

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
        endtoend_id: document.getElementById("endtoend_id").value,
        ip_src: document.getElementById("ip_src").value,
        ip_dst: document.getElementById("ip_dst").value,
        smpp_source_addr: document.getElementById("smpp-source_addr").value,
        smpp_destination_addr: document.getElementById("smpp-destination_addr")
            .value,
        body: document.getElementById("body").value,
        method: document.getElementById("filter_method").value,
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
    if (parameters.endtoend_id == "") parameters.endtoend_id = "NA";
    if (parameters.ip_src == "") parameters.ip_src = "NA";
    if (parameters.ip_dst == "") parameters.ip_dst = "NA";
    if (parameters.smpp_source_addr == "") parameters.smpp_source_addr = "NA";
    if (parameters.smpp_destination_addr == "") parameters.smpp_destination_addr = "NA";
    if (parameters.method == "") parameters.method = "NA";
    if (parameters.ip_from == "") parameters.ip_from = "NA";
    if (parameters.ip_to == "") parameters.ip_to = "NA";
    if (parameters.body == "") parameters.body = "NA";
    if (parameters.filterLogic == "") parameters.filterLogic = "AND";

    return parameters;
}

function updateDiagram() {
    let params = buildParameterList();

    if(params == null)
        return;

    //Cleaning everything before population
    $('#resultsWrapper').empty();
    currentProtocolSlide = 0;
    selectedProtocols = [];

    //Populating selected list with the results
    protocols.forEach(protocolObject => {
        $("#packetAmtIndicator-" + protocolObject.protocolName).text("Loading...");
        $("#packetAmtIndicator-" + protocolObject.protocolName).hide();

        if ($("#protocol-" + protocolObject.protocolName).is(':checked')) {
            console.info("Building Diagram For " + protocolObject.protocolName);
            protocolObject['protocolId'] = protocolObject.protocolName + '-' + uuidv4().replace(/-/g, '');
            requestProtocolDiagram(protocolObject, params.startDate, params.endDate, 
                params.imsi, params.msisdn, params.endtoend_id, params.ip_src, params.ip_dst,
                params.smpp_source_addr, params.smpp_destination_addr, params.method, params.ip_from, params.ip_to, params.body, params.filterLogic);
        }
    });

    $('.carousel').carousel();
    buildProtocolNavigation();
}

function buildPcap() {
    let params = buildParameterList();
    let fileIndexArray = [];
    let jsonRequest = {};
    let currentProtocol = selectedProtocols[currentProtocolSlide].protocolName;

    if(params == null)
        return;

    $('#loadingModal').modal('show');

    let rowSelect = $("#pivot-table-" + selectedProtocols[currentProtocolSlide].protocolId + " table tbody tr > td input.generate_pcap:checked").parent().parent();
    
    if (rowSelect.length > 0) {
        for (let i = 0; i < rowSelect.length; i++) {
            fileIndexArray.push({
                file_index : rowSelect[i].querySelector(".file_index").innerText,
                frame_number : rowSelect[i].querySelector(".frame_number").innerText, 
                protocol_name : currentProtocol
            });
        }
    }

    jsonRequest = {
        payload: fileIndexArray,
        parameters: params
    }

    $.ajax({
        url: deploymentUrl + "/getPcap",
        contentType: "application/json",
        type: 'POST',
        data: JSON.stringify(jsonRequest),
        timeout: 300000,

        success: function (data) {
            window.location.href = deploymentUrl + '/download/'+data.Result;

            $('#loadingModal').modal('hide');
            toastr.info('PCAP file downloaded');
        },
        error: function (data) {
            toastr.error('Could not extract PCAP file.');
            setTimeout(function () { $('#loadingModal').modal('hide'); }, 500);
        },
    });

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
        parameters: params
    }

    $.ajax({
        url: deploymentUrl + "/getPcap",
        contentType: "application/json",
        type: 'POST',
        data: JSON.stringify(jsonRequest),
        timeout: 300000,

        success: function (data) {
            window.location.href = deploymentUrl + '/download/'+data.Result;

            $('#loadingModal').modal('hide');
            toastr.info('PCAP file downloaded');
        },
        error: function (data) {
            toastr.error('Could not extract PCAP file.');
            setTimeout(function () { $('#loadingModal').modal('hide'); }, 500);
        },
    });

}

function addToSelectedList(protocolObject) {
    var index = selectedProtocols.findIndex(x => x.protocolName == protocolObject.protocolName);

    if (index < 0)
        selectedProtocols.push(protocolObject);
    else
        selectedProtocols[index] = protocolObject;
}

function requestProtocolDiagram(protocolObject, startDate, endDate, imsi, msisdn, endtoend_id, ip_src, ip_dst, smpp_source_addr, smpp_destination_addr, method, ip_from, ip_to, body, filterLogic) {

    var protocolName = protocolObject.protocolName;
    var diagramHolder = diagramTemplate.replaceAll("${protocol}", protocolObject.protocolId);
    var loadingSpinner = spinnerTemplate.replaceAll("${protocol}", protocolObject.protocolId);
    var itemClassNames = "carousel-item active";

    addToSelectedList(protocolObject);

    $("#packetAmtIndicator-" + protocolName).show();

    if ($("#carousel-item-" + protocolName).length < 1)
        $("#resultsWrapper").append($("<div/>", { class: itemClassNames, id: "carousel-item-" + protocolName }).html(loadingSpinner));

    $.ajaxSetup({
        timeout: 200000,
        retryAfter: 5000
    });

    $.ajax({
        url: deploymentUrl + "/buildDiagram/",
        contentType: "application/json",
        type: "POST",
        data: JSON.stringify({
            protocol: protocolName,
            startDate: startDate,
            endDate: endDate,
            imsi: imsi,
            msisdn: msisdn,
            endtoend_id: endtoend_id,
            ip_src: ip_src,
            ip_dst: ip_dst,
            smpp_src: smpp_source_addr,
            smpp_dst: smpp_destination_addr,
            method: method,
            ip_from: ip_from,
            ip_to: ip_to,
            body: body,
            filterLogic: filterLogic
        }),
        success: (data) => {

            if (data.length <= 2) {
                console.info("NO DATA FOR " + protocolName);
                $("#carousel-item-" + protocolName).remove();
                removeProtocolFromList(protocolName);
                $("#packetAmtIndicator-" + protocolName).text("Empty response.");

                return;
            }

            $("#packetAmtIndicator-" + protocolName).text(data.length - 2);

            let arr = [];

            for (const key in data) {
                if (Object.hasOwnProperty.call(data, key)) {
                    let element = data[key];
                    element = element.replaceAll(/((\d{4})-(\d\d)-(\d\d)T(\d\d):(\d\d):(\d\d).(\d\d\d)Z)/g, (str, p1, p2, offset, s) => {
                        return p1.replace('T', ' ').replace('Z', '');
                    });
                    arr.push(element);
                }
            }

            $("#DiagramsDrawingBoard").append($(diagramHolder));
            protocolObject.response = arr;
            protocolObject.diagram = arr.shift();
            protocolObject.htmlTable = arr.pop();
            protocolObject.table = arr;
            protocolObject.cantPacket = arr.length;
            protocolObject.currentPacket = 0;
            $("#pivot-table-" + protocolObject.protocolId + " table").html(protocolObject.htmlTable);

            try {
                var svgId = "diagram-" + protocolObject.protocolId;
                var diagram = Diagram.parse(protocolObject.diagram);
                diagram.drawSVG(svgId, { theme: 'simple' });
            } catch (e) {
                console.warn("Cannot build diagram for: " + protocolName + " due to: ", e)
            }
            var element = $('#diagram-content-' + protocolObject.protocolId).detach();
            $("#carousel-item-" + protocolName).html(element);

            setTimeout(() => {
                $('#resultsWrapper div:nth-child(1)').siblings().removeClass('active');
                enableGrabScrolling(protocolObject.protocolId);
                $("#download-pcap-" + protocolObject.protocolId).prop(
                    "disabled",
                    false
                );
            }, 1000);

            setTimeout(() => {
                let paddingRigth = 65;
                $("#diagram-" + protocolObject.protocolId + " g.signal").attr("transform", `translate(${paddingRigth}, -50)`);
                $("#diagram-" + protocolObject.protocolId + " svg.sequence").children("line").attr("transform", `translate(${paddingRigth}, 0)`);
                let widthDiagram = $("#diagram-" + protocolObject.protocolId + " svg.sequence").width();
                $("#diagram-" + protocolObject.protocolId + " svg.sequence").attr("width", `${widthDiagram + paddingRigth}`);
                $("#diagram-" + protocolObject.protocolId + " g.actor").children().attr("transform", `translate(${paddingRigth}, 0)`);

                let lastScrollTop = 0;
                $("#diagram-" + protocolObject.protocolId).scroll(() => {
                    let currentScrollTop = $("#diagram-" + protocolObject.protocolId).scrollTop();
                    let scrollAmount = currentScrollTop - lastScrollTop;
                    lastScrollTop = $("#diagram-" + protocolObject.protocolId).scrollTop();
                    let length = $("#diagram-" + protocolObject.protocolId + " g.actor").length;
                    for (var i = 0; i < length; i++) {
                        if (i % 2 == 0) {
                            let actor = $("#diagram-" + protocolObject.protocolId + " g.actor").eq(i);
                            let currentRectY = parseFloat(actor.children("rect").attr("y"));
                            let currentTextY = parseFloat(actor.children("text").attr("y"));

                            currentRectY = currentRectY + scrollAmount;
                            actor.children("rect").attr("y", `${currentRectY}`);
                            
                            currentTextY = currentTextY + scrollAmount;
                            actor.children("text").attr("y", `${currentTextY}`);
                        }
                    }
                });
            }, 100);
        },
        error: (data) => {
            //$("#carousel-item-"+protocolName).remove();
            console.warn('Ajax request failed... Will try soon...');
            // if ($("#carousel-item-" + protocolName).length < 1)
            // $("#resultsWrapper").html('<h4 style="color:red">Sorry, an error has ocurred, please try again or contact the administrator</h4>');
            $("#packetAmtIndicator-" + protocolName).text("Error...");
            //setTimeout(() => { requestProtocolDiagram(protocolObject, startDate, endDate, imsi, msisdn, endtoend_id, ip_src, ip_dst, smpp_source_addr, smpp_destination_addr, method, ip_from, ip_to, body, filterLogic) }, $.ajaxSetup().retryAfter);
        }
    });

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

function buildProtocolNavigation() {


    if (navigationAlreadyAdded == false) {
        var wrapper = $(".protocol-navigation").parent();
        $(wrapper).append($(buttonsTemplate));
        navigationAlreadyAdded = true;
    }

}

function removeProtocolFromList(protocolName) {
    $("#carousel-item-" + protocolName).remove();

    selectedProtocols = selectedProtocols.filter(function (value, index, arr) {
        return value.protocolName != protocolName;
    });
}
