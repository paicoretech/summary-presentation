@extends('dashboard.base')

@section('css')
<style>
    .fixed-btn {
        width: 120px !important;
        text-align: center;
        display: inline-block;
    }
    .btn-gap {
        margin-left: 10px;
    }
    .warning-icon-large {
        font-size: 3rem;
        color: #ffc107;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="card">
        <div class="card-header card-header-filter d-flex justify-content-between align-items-center">
            <h3>Downloads</h3>
            <button type="button">
                <img src="{{ asset('assets/icons/download-icon.svg') }}" alt="Download" class="arrow-integrated" />
            </button>
        </div>

        <div class="card-body">
            <form id="filterForm" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="btn-protocol">
                            <select id="status" class="form-control download-status">
                                <option value="">All Statuses</option>
                                <option value="QUEUED">Queued</option>
                                <option value="IN_PROGRESS">In Progress</option>
                                <option value="COMPLETED">Completed</option>
                                <option value="FAILED">Failed</option>
                                <option value="CANCELLED">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="input-group date datetime-picker" id="datepickerIntegratedStart" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#datepickerIntegratedStart" id="start_date"/>
                            <div class="input-group-append input-calendar" data-target="#datepickerIntegratedStart" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <div class="input-group date datetime-picker" id="datepickerIntegratedEnd" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#datepickerIntegratedEnd" id="end_date"/>
                            <div class="input-group-append input-calendar" data-target="#datepickerIntegratedEnd" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-protocol">
                            <button type="submit" class="btn btn-dark-red w-100 h-100 btn-filter-download" >Apply Filters</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Dynamic List -->
            <div id="downloadsList"></div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>

            <!-- Loader -->
            <div id="loading" class="text-center d-none">
                <div class="spinner-border text-dark-red" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center text-muted d-none">
                <p>No downloads found with the current filters.</p>
            </div>
        </div>
    </div>
</div>

<!-- Job Clear Job Clear Warning Dialog -->
<div class="modal fade" id="clearWarningModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fa fa-exclamation-triangle warning-icon-large"></i>
                </div>
                <h4 class="mb-3">Are you sure you want to remove this job?</h4>
                <p id="clearModalText" class="text-muted mb-4"></p>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary mr-3 fixed-btn" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-dark-red fixed-btn"
                            style="background-color: #7B3031; color: white; border-color: #7B3031;"
                            onclick="confirmClear()">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Job Cancel Job Clear Warning Dialog -->
<div class="modal fade" id="cancelWarningModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fa fa-exclamation-triangle warning-icon-large"></i>
                </div>
                <h4 class="mb-3">Are you sure to cancel this job?</h4>
                <p id="cancelModalText" class="text-muted mb-4"></p>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary mr-3 fixed-btn" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-dark-red fixed-btn"
                            style="background-color: #7B3031; color: white; border-color: #7B3031;"
                            onclick="confirmCancel()">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Connection Error Warning Dialog -->
<div class="modal fade" id="connectionErrorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fa fa-exclamation-circle" style="font-size: 3rem; color: #dc3545;"></i>
                </div>
                <h4 class="mb-3">Connection Failed</h4>
                <p id="connectionErrorText" class="text-muted mb-4">
                    Connection could not be established. Please contact the administrator.
                </p>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary fixed-btn" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const analyticsBaseUrl = "{{ config('services.ANALYTICS_SERVICE_URL') }}";
    const appBaseUrl = "{{ url('/downloads') }}";

    let currentPage = 0;
    const pageSize = 10;
    let activeFilters = { status: '', startDate: '', endDate: '' };

    const activeStreams = {};
    let jobToClear = null;
    let jobToClearIsActive = false;
    let jobToCancel = null;
    let lastFailedAction = null;

    // Initial Load
    document.addEventListener('DOMContentLoaded', () => {
        fetchDownloads();
    });

    /**
     * Retrieves the list of jobs from the server.
     */

    async function fetchDownloads() {
        document.getElementById('loading').classList.remove('d-none');
        document.getElementById('downloadsList').innerHTML = '';

        try {
            const params = new URLSearchParams({ page: currentPage, size: pageSize });
            if (activeFilters.status) params.append('status', activeFilters.status);
            if (activeFilters.startDate) params.append('startDate', activeFilters.startDate);
            if (activeFilters.endDate) params.append('endDate', activeFilters.endDate);

            const response = await fetch(`{{ route('downloads.list') }}?${params.toString()}`);
            if (response.ok) {
                const data = await response.json();
                const jobs = (data.content || []);
                renderJobs(jobs);
                renderPagination(data);

                jobs.forEach(job => {
                    if (job.status === 'IN_PROGRESS' || job.status === 'QUEUED') {
                        setupJobStream(job.jobId);
                    }
                });
                toggleEmptyState(jobs.length === 0);
            }
        } catch (e) {
        } finally {
            document.getElementById('loading').classList.add('d-none');
        }
    }

    document.getElementById('filterForm').addEventListener('submit', function (e) {
        e.preventDefault();
        currentPage = 0;
        const rawStart = document.getElementById("start_date").value;
        const rawEnd = document.getElementById("end_date").value;
        activeFilters = {
            status: document.getElementById('status').value,
            startDate: rawStart ? moment(rawStart, "DD/MM/YYYY HH:mm:ss").format("YYYY-MM-DDTHH:mm:ss") : '',
            endDate: rawEnd ? moment(rawEnd, "DD/MM/YYYY HH:mm:ss").format("YYYY-MM-DDTHH:mm:ss") : '',
        };
        fetchDownloads();
    });


    function setupJobStream(jobId) {
        if (activeStreams[jobId]) return;

        const source = new EventSource(`${analyticsBaseUrl}/v2/stream/${jobId}`);
        activeStreams[jobId] = source;

        source.addEventListener('progress', function(e) {
            try {
                const data = JSON.parse(e.data);
                updateSingleJobUI(jobId, data.status, data.progress);
                if (data.status === 'COMPLETED' || data.status === 'FAILED' || data.status === 'CANCELLED') {
                    source.close();
                    delete activeStreams[jobId];
                }
            } catch (err) { }
        });

        source.onerror = function() {
            source.close();
            delete activeStreams[jobId];

            const statusSpan = document.getElementById(`status-${jobId}`);
            const progressBar = document.getElementById(`progress-${jobId}`);
            const actionDiv = document.getElementById(`action-${jobId}`);

            if (statusSpan) {
                statusSpan.innerText = "Connection Interrupted";
                statusSpan.className = "text-danger font-weight-bold";
            }
            if (progressBar) {
                progressBar.classList.remove('progress-bar-animated');
                progressBar.style.backgroundColor = '#6c757d';
            }

            if (actionDiv) {
                const btn = actionDiv.querySelector('button');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = "Offline";
                    btn.style.opacity = "0.5";
                    btn.style.cursor = "not-allowed";
                }
            }
        };
    }


    function updateSingleJobUI(jobId, status, progress) {
        const progressBar = document.getElementById(`progress-${jobId}`);
        const statusSpan = document.getElementById(`status-${jobId}`);
        const actionDiv = document.getElementById(`action-${jobId}`);
        if (!progressBar || !statusSpan) return;

        const style = getProgressBarStyle(status, progress);
        progressBar.style.width = style.width;
        progressBar.innerText = style.text;
        progressBar.className = `progress-bar ${style.class}`;
        progressBar.style.cssText = `width: ${style.width}; transition: width 0.5s ease; ${style.inline}`;
        statusSpan.innerText = status;

        if (actionDiv) actionDiv.innerHTML = getActionBtn({ jobId: jobId, status: status, progress: progress });
    }

    function renderJobs(jobs) {
        const list = document.getElementById('downloadsList');
        list.innerHTML = '';
        jobs.forEach(job => { list.innerHTML += createJobCard(job); });
    }

    function createJobCard(job) {
        const style = getProgressBarStyle(job.status, job.progress);
        return `
            <div id="job-${job.jobId}" class="download-item mb-3 p-3 border rounded shadow-sm d-flex justify-content-between align-items-center">
                <div class="w-75">
                    <strong>${job.fileName || 'Pending file'}</strong>
                    <p class="mb-1"><strong>Job ID:</strong> ${job.jobId}</p>
                    <p class="mb-1"><strong>Status:</strong> <span id="status-${job.jobId}">${job.status}</span></p>
                    <div class="progress mb-2">
                        <div id="progress-${job.jobId}" class="progress-bar ${style.class}" role="progressbar"
                             style="width: ${style.width}; transition: width 0.5s ease; ${style.inline}" aria-valuenow="${job.progress || 0}">${style.text}</div>
                    </div>
                    <small class="text-muted">${new Date(job.createdAt || Date.now()).toLocaleString()}</small>
                </div>
                <div class="d-flex align-items-center" id="action-${job.jobId}">${getActionBtn(job)}</div>
            </div>`;
    }

    function getActionBtn(job) {
        const clearBtn = `<button class="btn btn-dark-red fixed-btn btn-gap" style="background-color: #7B3031; color: white;" onclick="clearJob('${job.jobId}', '${job.status}')">Clear</button>`;
        if (job.status === 'IN_PROGRESS' || job.status === 'QUEUED') {
            return `<button class="btn btn-dark-red fixed-btn" style="background-color: #7B3031; color: white;" onclick="cancelJob('${job.jobId}')">Cancel</button>`;
        }
        if (job.status === 'COMPLETED') {
            return `<button class="btn btn-dark-red fixed-btn" style="background-color: #7B3031; color: white;" onclick="initiateDownload('${job.jobId}')">Download</button>${clearBtn}`;
        }
        return clearBtn;
    }

    function getProgressBarStyle(status, progress) {
        let style = { class: 'bg-secondary', width: '0%', inline: '', text: 'Pending' };
        if (status === 'IN_PROGRESS') {
            style = { class: 'progress-bar-animated progress-bar-striped', width: `${progress || 0}%`, inline: 'background-color: #7B3031;', text: `${progress || 0}%` };
        } else if (status === 'COMPLETED') {
            style = { class: 'bg-success', width: '100%', inline: '', text: '100%' };
        } else if (status === 'FAILED') {
            style = { class: '', width: '100%', inline: 'background-color: #7B3031; color: white;', text: 'Failed' };
        } else if (status === 'CANCELLED') {
            style = { class: '', width: '100%', inline: 'background-color: #ffc107; color: #343a40; font-weight: bold;', text: 'Cancelled' };
        }
        return style;
    }


    function cancelJob(jobId) {
    jobToCancel = jobId;
    document.getElementById('cancelModalText').innerHTML = `Job ID: <strong>${jobId}</strong>`;
    $('#cancelWarningModal').modal('show');
}

    async function confirmCancel() {
        if (jobToCancel) {
            $('#cancelWarningModal').modal('hide');
            await executeCancelJob(jobToCancel);
            jobToCancel = null;
        }
    }

    async function executeCancelJob(jobId) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch(`${appBaseUrl}/${jobId}/cancel`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
            });
            if (response.ok) {
                updateSingleJobUI(jobId, 'CANCELLED', 100);
                if (activeStreams[jobId]) { activeStreams[jobId].close(); delete activeStreams[jobId]; }
            }
        } catch(e) { }
    }


    function clearJob(jobId, status) {
        jobToClear = jobId;
        jobToClearIsActive = (status === 'IN_PROGRESS' || status === 'QUEUED');
        let bodyContent = `Job ID: <strong>${jobId}</strong>`;
        if (jobToClearIsActive) {
            bodyContent += `<br><small class="text-danger">This job is currently <strong>ACTIVE</strong>. Clearing it will <strong>Force Cancel</strong> it.</small>`;
        }
        document.getElementById('clearModalText').innerHTML = bodyContent;
        $('#clearWarningModal').modal('show');
    }

    async function confirmClear() {
        if (jobToClear) {
            $('#clearWarningModal').modal('hide');
            await performClearSequence(jobToClear, jobToClearIsActive);
            jobToClear = null;
        }
    }

    async function performClearSequence(jobId, isActive) {
        const card = document.getElementById(`job-${jobId}`);
        try {
            const response = await fetch(`${analyticsBaseUrl}/v2/${jobId}/clear`, { method: 'POST' });
            if (!response.ok) throw new Error();
            if(card) card.remove();
            if (activeStreams[jobId]) { activeStreams[jobId].close(); delete activeStreams[jobId]; }
            if (isActive) await executeCancelJob(jobId);
            setTimeout(() => fetchDownloads(), 500);
        } catch(e) {
            document.getElementById('connectionErrorText').innerHTML = `Connection could not be established.`;
            $('#connectionErrorModal').modal('show');
        }
    }

    async function initiateDownload(jobId) {
        const downloadUrl = `${analyticsBaseUrl}/v2/download/${jobId}`;
        const errorText = document.getElementById('connectionErrorText');

        try {
            await fetch(downloadUrl, { method: 'HEAD', mode: 'no-cors' });
            window.location.href = downloadUrl;
        } catch (e) {
            $('#connectionErrorModal').modal('show');
        }
    }

    document.getElementById('retryActionBtn').addEventListener('click', async function() {
        if (lastFailedAction) {
            $('#connectionErrorModal').modal('hide');
            await lastFailedAction();
            lastFailedAction = null;
        }
    });

    function renderPagination(data) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';
        if (data.totalPages <= 1) return;
        const maxVisible = 5;
        let start = Math.max(0, data.number - Math.floor(maxVisible / 2));
        let end = Math.min(data.totalPages, start + maxVisible);
        if (end - start < maxVisible) start = Math.max(0, end - maxVisible);

        const createItem = (label, page, active, disabled) => {
            const li = document.createElement('li');
            li.className = `page-item ${active ? 'active' : ''} ${disabled ? 'disabled' : ''}`;
            li.innerHTML = `<button class="page-link" onclick="if(!${active} && !${disabled}) { currentPage=${page}; fetchDownloads(); }">${label}</button>`;
            return li;
        };
        pagination.appendChild(createItem('Prev', data.number - 1, false, data.first));
        for (let i = start; i < end; i++) pagination.appendChild(createItem(i + 1, i, i === data.number, false));
        pagination.appendChild(createItem('Next', data.number + 1, false, data.last));
    }

    function toggleEmptyState(isEmpty) {
        document.getElementById('emptyState').classList.toggle('d-none', !isEmpty);
    }
</script>
<script src="{{ asset('js/integratedSequenceDiagram.js') }}"></script>
<script src="{{ asset('js/popovers.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
@endsection
