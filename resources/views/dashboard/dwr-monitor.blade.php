@extends('dashboard.base')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}" crossorigin="anonymous" />
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
<style>
    .badge-cer { background-color: #2c5f8a; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
    .badge-dwr { background-color: #7B3031; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
    .badge-der { background-color: #e07b00; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
    .badge-dpr { background-color: #5a2d82; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
    .pcap-link { color: #7B3031; text-decoration: none; font-size: 0.8rem; cursor: pointer; }
    .pcap-link:hover { text-decoration: underline; color: #5a2020; }
    .pcap-link svg { margin-left: 4px; vertical-align: middle; }
    .filter-btn-active { background-color: #7B3031 !important; color: white !important; border-color: #7B3031 !important; }
    .input-calendar { cursor: pointer; }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="card">
        <div class="card-header card-header-filter d-flex justify-content-between align-items-center">
            <h3 style="color:#7B3031;">Diameter Peer Connection Events</h3>
            <button type="button" onclick="exportCsv()" style="border:none; background:transparent; padding:0;">
                <img src="{{ asset('assets/icons/download-icon.svg') }}" alt="Export CSV" class="arrow-integrated" />
            </button>
        </div>

        <div class="card-body">
            {{-- Filters Row --}}
            <div class="mb-4">
                <div class="row align-items-end">
                    <div class="form-group col-md-4 mb-0">
                        <label for="start_date" style="color:#7B3031; font-weight:bold;">Start date</label>
                        <div class="input-group date" id="datepickerStart" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input"
                                   data-target="#datepickerStart" id="start_date"/>
                            <div class="input-group-append input-calendar"
                                 data-target="#datepickerStart" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4 mb-0">
                        <label for="end_date" style="color:#7B3031; font-weight:bold;">End date</label>
                        <div class="input-group date" id="datepickerEnd" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input"
                                   data-target="#datepickerEnd" id="end_date"/>
                            <div class="input-group-append input-calendar"
                                 data-target="#datepickerEnd" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-2 mb-0">
                        <button class="btn w-100" onclick="loadData()"
                                style="background-color:#7B3031; color:white; border-color:#7B3031;">
                            <i class="fa fa-search mr-1"></i> Search
                        </button>
                    </div>
                </div>

                {{-- Type filter buttons --}}
                <div class="row mt-3">
                    <div class="col-12 d-flex align-items-center flex-wrap">
                        <div class="btn-group mr-3" role="group">
                            <button type="button" class="btn btn-sm filter-btn-active" id="filter-all" onclick="filterTable('ALL')">All</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="filter-cer" onclick="filterTable('CER')">CER — Connection Failed</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="filter-dwr" onclick="filterTable('DWR')">DWR — Link Dropped</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="filter-der" onclick="filterTable('DER')">DER — Auth Failed</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="filter-dpr" onclick="filterTable('DPR')">DPR — Disconnect</button>
                        </div>
                        <small id="record-count" class="text-muted"></small>
                    </div>
                </div>
            </div>

            {{-- Loading --}}
            <div id="loading" class="text-center d-none">
                <div class="spinner-border" role="status" style="color:#7B3031;">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            {{-- Error --}}
            <div id="error-msg" class="alert alert-danger d-none"></div>

            {{-- Table --}}
            <div style="max-height:65vh; overflow-y:auto;">
                <table class="table table-hover table-bordered table-sm mb-0">
                    <thead style="background-color:#7B3031; color:#fff; position:sticky; top:0; z-index:1;">
                        <tr>
                            <th style="width:40px;">#</th>
                            <th style="width:160px;">Timestamp</th>
                            <th>Source Peer</th>
                            <th>Destination Peer</th>
                            <th style="width:70px;">Type</th>
                            <th>PCAP File</th>
                        </tr>
                    </thead>
                    <tbody id="dwr-body">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Select a date range and click <strong>Search</strong>.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="emptyState" class="text-center text-muted d-none mt-3">
                <p>No unanswered Diameter alarms found in this time range.</p>
            </div>

            <div class="mt-2 text-right">
                <small id="record-count-bottom" class="text-muted"></small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script>
    let currentData = [];
    let activeFilter = 'ALL';
    const backendUrl = '{{ config("services.ANALYTICS_SERVICE_URL") }}';

    $(document).ready(function () {
        $("#datepickerStart").datetimepicker({
            format: "DD/MM/YYYY HH:mm:ss",
            defaultDate: moment().startOf("date").format("YYYY-MM-DD HH:mm")
        });
        $("#datepickerEnd").datetimepicker({
            format: "DD/MM/YYYY HH:mm:ss",
            defaultDate: moment().endOf("date").format("YYYY-MM-DD HH:mm")
        });
    });

    function getBrowserTimezone() {
        return Intl.DateTimeFormat().resolvedOptions().timeZone;
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    async function loadData() {
        const rawStart = document.getElementById('start_date').value;
        const rawEnd   = document.getElementById('end_date').value;

        if (!rawStart || !rawEnd) { alert('Please select both start and end date.'); return; }

        const startDate = moment(rawStart, 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
        const endDate   = moment(rawEnd,   'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
        const timezone  = getBrowserTimezone();

        const loading  = document.getElementById('loading');
        const errorDiv = document.getElementById('error-msg');
        const tbody    = document.getElementById('dwr-body');
        const empty    = document.getElementById('emptyState');

        loading.classList.remove('d-none');
        errorDiv.classList.add('d-none');
        empty.classList.add('d-none');
        tbody.innerHTML = '';
        document.getElementById('record-count').textContent = '';
        document.getElementById('record-count-bottom').textContent = '';

        try {
            const res = await fetch('{{ route("dwr-monitor.fetch") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ startDate, endDate, timezone })
            });

            if (!res.ok) throw new Error(`Server responded with ${res.status}`);
            const data = await res.json();
            currentData = data;
            filterTable(activeFilter);

        } catch (err) {
            errorDiv.textContent = `Failed to fetch: ${err.message}`;
            errorDiv.classList.remove('d-none');
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">No data loaded.</td></tr>`;
        } finally {
            loading.classList.add('d-none');
        }
    }

    function filterTable(type) {
        activeFilter = type;
        ['all','cer','dwr','der','dpr'].forEach(t => {
            const btn = document.getElementById('filter-' + t);
            btn.classList.remove('filter-btn-active');
            btn.classList.add('btn-outline-secondary');
        });
        const activeBtn = document.getElementById('filter-' + type.toLowerCase());
        activeBtn.classList.add('filter-btn-active');
        activeBtn.classList.remove('btn-outline-secondary');
        const filtered = type === 'ALL' ? currentData : currentData.filter(r => r.alarmType === type);
        renderTable(filtered);
    }

    function renderTable(data) {
        const tbody = document.getElementById('dwr-body');
        const empty = document.getElementById('emptyState');

        if (!data || data.length === 0) {
            tbody.innerHTML = '';
            empty.classList.remove('d-none');
            document.getElementById('record-count').textContent = '0 records found';
            document.getElementById('record-count-bottom').textContent = '0 records found';
            return;
        }

        empty.classList.add('d-none');
        const count = `${data.length} record${data.length !== 1 ? 's' : ''} found`;
        document.getElementById('record-count').textContent = count;
        document.getElementById('record-count-bottom').textContent = count;

        tbody.innerHTML = data.map((r, i) => `
            <tr>
                <td>${i + 1}</td>
                <td>${escHtml(r.timestamp)}</td>
                <td>${escHtml(r.srcPeer)}</td>
                <td>${escHtml(r.dstPeer)}</td>
                <td><span class="badge-${r.alarmType.toLowerCase()}">${escHtml(r.alarmType)}</span></td>
                <td><small>${escHtml(r.pcapFile)}</small></td>
            </tr>
        `).join('');
    }

    function downloadTrace(filename) {
        const url = backendUrl + '/dwr/trace/download?filename=' + encodeURIComponent(filename);
        window.open(url, '_blank');
    }

    function exportCsv() {
        const filtered = activeFilter === 'ALL' ? currentData : currentData.filter(r => r.alarmType === activeFilter);
        if (!filtered || filtered.length === 0) {
            alert('No data to export. Fetch records first.');
            return;
        }
        const rows = [
            ['#','Timestamp','Source Peer','Destination Peer','Type','PCAP File'],
            ...filtered.map((r, i) => [i+1, r.timestamp, r.srcPeer, r.dstPeer, r.alarmType, r.pcapFile])
        ].map(r => r.join(',')).join('\n');

        const a = Object.assign(document.createElement('a'), {
            href: URL.createObjectURL(new Blob([rows], {type:'text/csv'})),
            download: `diameter_alarms_${new Date().toISOString().slice(0,10)}.csv`
        });
        a.click();
    }
</script>
@endsection