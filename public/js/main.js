"use strict"

document.addEventListener('DOMContentLoaded', function (event) {
    
    // instanciation de la table dynamique en page d'accueil
    let table = new DataTable('.js-table', {
        paging: false,
        searching: false,
        info: false,
        columns: [
            { orderable: false },
            { orderable: false },
            null,
            null,
            { orderable: false },
            { orderable: false }
          ],
        order: [2, "desc"]
    });

    // action sur les formulaires pour être postés et traités de manière synchrone.
    ajaxFormSubmission("form[name='dossier_form']", "#modalDossier .modal-body");
    ajaxFormSubmission("form[name='ecriture_form']", "#modalEcriture .modal-body");

    // action pour récupérer un formulaire (et l'hydrater) et le poster/traiter de manière asynchrone
    ajaxFormFetchAndSubmission("button[data-bs-target='#modalEditEcriture']", "#modalEditEcriture .modal-body");

})