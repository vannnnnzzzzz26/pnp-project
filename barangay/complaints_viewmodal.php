<!-- Complaint Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="complaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                <p><strong>Description:</strong> <span id="modal-description"></span></p>
                <p><strong>Category:</strong> <span id="modal-category"></span></p>
                <p><strong>Barangay:</strong> <span id="modal-barangay"></span></p>
                <p><strong>Contact:</strong> <span id="modal-contact"></span></p>
                <p><strong>Person:</strong> <span id="modal-person"></span></p>
                <p><strong>Gender:</strong> <span id="modal-gender"></span></p>
                <p><strong>Birth Place:</strong> <span id="modal-birth_place"></span></p>
                <p><strong>Age:</strong> <span id="modal-age"></span></p>
                <p><strong>Education:</strong> <span id="modal-education"></span></p>
                <p><strong>Civil Status:</strong> <span id="modal-civil_status"></span></p>
                <p><strong>Evidence:</strong> 
                    <span id="modal-evidence">
                        <img id="modal-evidence-image" src="" alt="Evidence Image" style="display: none; max-width: 100%;">
                        <video id="modal-evidence-video" controls style="display: none; max-width: 100%;">
                            <source id="modal-evidence-video-source" src="" type="video/mp4">
                        </video>
                    </span>
                </p>
                <p><strong>Date Filed:</strong> <span id="modal-date_filed"></span></p>
                <p><strong>Hearing Date:</strong> <span id="modal-hearing_date"></span></p>
                <p><strong>Hearing Time:</strong> <span id="modal-hearing_time"></span></p>
                <p><strong>Hearing Type:</strong> <span id="modal-hearing_type"></span></p>
                
                <!-- New button for viewing hearing history -->
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#hearingHistoryModal">View Hearing History</button>
                
                <p><strong>Hearing Status:</strong> <span id="modal-hearing_status"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="moveToPnpBtn">Move to PNP</button>
                <button type="button" class="btn btn-secondary" id="settleInBarangayBtn">Settle in Barangay</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewComplaintModal" id="setHearingBtn">
                    Set Hearing
                </button>
            </div>
        </div>
    </div>
</div>
