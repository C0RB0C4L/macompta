"use strict"

document.addEventListener('DOMContentLoaded', function (event) {
    
    let table = new DataTable('.js-table', {
        paging: false,
        searching: false,
        columns: [
            { orderable: false },
            { orderable: false },
            null,
            null,
            { orderable: false },
            { orderable: false }
          ]
    });

    ajaxFormSubmission("form[name='dossier_form']", "#modalDossier .modal-body");
    ajaxFormSubmission("form[name='ecriture_form']", "#modalEcriture .modal-body");

    ajaxFormFetchAndSubmission("button[data-bs-target='#modalEditEcriture']", "#modalEditEcriture .modal-body");

})