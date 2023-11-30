"use strict"

document.addEventListener('DOMContentLoaded', function (event) {

    // profile - my account
    ajaxFormSubmission("form[name='dossier_form']", "#modalDossier .modal-body");
})

/**
     * @description Handles all the submission / reload (for errors) process inside ajax form.
     * 
     * @return void
     */
function ajaxFormSubmission(formSelector, containerSelector) {

    let form = document.querySelector(formSelector);
    
    if (form !== null) {

        let submitBtn = form.querySelector("button[type='submit']");
        let container = document.querySelector(containerSelector);

        if (submitBtn !== null && container !== null) {

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                let ajaxForm = new FormData(form);

                fetch(submitBtn.getAttribute('formaction'), {
                    method: 'POST',
                    headers: new Headers({ "X-Requested-With": "XMLHttpRequest" }),
                    body: ajaxForm
                })
                    .then(response => response.json())
                    .then(response => {

                        if (response.status !== undefined && response.status === 0) {
                            let DOMResponse = new DOMParser().parseFromString(response.body, "text/html");
                            container.innerHTML = "";
                            container.append(DOMResponse.querySelector(formSelector));
                            ajaxFormSubmission(formSelector, containerSelector);
                            formSubmissionSpinner();
                            enableHighlightIfFieldsAreDifferent();
                            enableSelect2Input();
                        }

                        if (response.status !== undefined && response.status === 1) {
                            window.location.href = response.url;
                        }
                    })
            })
        }
    }
}