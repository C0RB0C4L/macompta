"use strict"

document.addEventListener('DOMContentLoaded', function (event) {

    // profile - my account
    ajaxFormSubmission("form[name='dossier_form']", "#modalDossier .modal-body");
    ajaxFormSubmission("form[name='ecriture_form']", "#modalEcriture .modal-body");
    
    
    ajaxFormFetchAndSubmission("button[data-bs-target='#modalEditEcriture']", "#modalEditEcriture .modal-body");
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
                        }

                        if (response.status !== undefined && response.status === 1) {
                            window.location.href = response.url;
                        }
                    })
            })
        }
    }
}

/**
     * @description Handles all the submission / reload (for errors) process inside ajax form.
     * 
     * @return void
     */
function ajaxFormFetchAndSubmission(fetcherSelector, containerSelector) {

    let fetchers = document.querySelectorAll(fetcherSelector);
    let container = document.querySelector(containerSelector);

    if (fetchers.length !== 0 && container !== null) {

        for (let fetcher of fetchers) {
            
            fetcher.addEventListener("click", function (e) {
                e.preventDefault();

                let url = fetcher.getAttribute("data-form-fetch");

                ajaxFetchSpinner(containerSelector, true);

                fetch(url, {
                    method: 'GET',
                    headers: new Headers({ "X-Requested-With": "XMLHttpRequest" }),
                })
                    .then(response => response.json())
                    .then(response => {

                        if (response.status !== undefined && response.status === 0) {
                            let DOMResponse = new DOMParser().parseFromString(response.body, "text/html");
                            console.log(DOMResponse);
                            let form = DOMResponse.querySelector("form");
                            ajaxFetchSpinner(containerSelector, false);
                            container.innerHTML = "";
                            container.append(form);
                            formSubmissionSpinner();

                            ajaxFormSubmission("form#" + form.getAttribute('id'), containerSelector);
                        }
                    })

            })

        }
    }
}


/**
 * @description Adds or removes a spinner inside the desired \
 * Works with bootstrap v5.2.x.
 * 
 * @return void
 */
function ajaxFetchSpinner(selector, bool) {

    let container = document.querySelector(selector);
    let spinner = '<div class="d-flex justify-content-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    if (bool) {
        container.innerHTML = spinner;
    } else {
        container.innerHTML = '';
    }
}

/**
 * @description Replaces the text by a spinner gif on the submit button when a form is sent.\
 * Works with bootstrap v5.2.x.
 * 
 * @return void
 */
function formSubmissionSpinner() {
    let forms = document.querySelectorAll("form");

    if (forms.length > 0) {
        for (let form of forms) {
            form.addEventListener('submit', function (e) {
                let submitBtn = form.querySelector("button[type='submit']");
                let currentWidth = submitBtn.clientWidth;
                submitBtn.classList.add("disabled");
                submitBtn.style.minWidth = currentWidth.toString() + 'px'
                submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
            })
        }
    }
}