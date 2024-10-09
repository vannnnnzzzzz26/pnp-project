<!-- Complaint Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="complaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div style="display: flex; flex-wrap: wrap;">
    <!-- First Column -->
    <div style="flex: 1; min-width: 300px; padding-right: 20px;">
        <label><strong>Name:</strong></label>
        <input type="text" id="modal-name" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Ano (What):</strong></label>
        <input type="text" id="modal-ano" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Saan (Where):</strong></label>
        <input type="text" id="modal-saan" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Kailan (When):</strong></label>
        <input type="text" id="modal-kailan" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Paano (How):</strong></label>
        <input type="text" id="modal-paano" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Bakit (Why):</strong></label>
        <input type="text" id="modal-bakit" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Description:</strong></label>
        <input type="text" id="modal-description" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Category:</strong></label>
        <input type="text" id="modal-category" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Barangay:</strong></label>
        <input type="text" id="modal-barangay" style="display: block; margin-bottom: 10px; width: 100%;">
    </div>

    <!-- Second Column -->
    <div style="flex: 1; min-width: 300px; padding-left: 20px;">
        <label><strong>Contact:</strong></label>
        <input type="text" id="modal-contact" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Person:</strong></label>
        <input type="text" id="modal-person" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Gender:</strong></label>
        <input type="text" id="modal-gender" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Birth Place:</strong></label>
        <input type="text" id="modal-birth_place" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Age:</strong></label>
        <input type="text" id="modal-age" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Education:</strong></label>
        <input type="text" id="modal-education" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Civil Status:</strong></label>
        <input type="text" id="modal-civil_status" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Date Filed:</strong></label>
        <input type="text" id="modal-date_filed" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Status:</strong></label>
        <input type="text" id="modal-status" style="display: block; margin-bottom: 10px; width: 100%;">

        <label><strong>Nationality:</strong></label>
        <input type="text" id="modal-nationality" style="display: block; margin-bottom: 10px; width: 100%;">
    </div>
</div>

<!-- Hearing History Section -->
<div id="modalHearingHistorySection">
    <!-- Hearing history will be populated here -->
</div>

<!-- Evidence Section -->
<div id="modalEvidenceSection" style="display: none;">
    <p><strong>Evidence:</strong></p>
    <ul id="modalEvidenceList"></ul>
</div>


            <div class="modal-footer">
             <!-- Buttons -->
<button type="button" class="btn btn-secondary" id="moveToPnpBtn">Move to PNP</button>
<button type="button" class="btn btn-secondary" id="settleInBarangayBtn">Settle in Barangay</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewComplaintModal" id="setHearingBtn">
                    Set Hearing
                </button>
            </div>
        </div>
    </div>
</div>
