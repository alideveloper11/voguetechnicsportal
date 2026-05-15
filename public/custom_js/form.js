$(document).ready(function () {
    $('.modal').on('hidden.bs.modal', function () {
        const $form = $(this).find('form');

        if ($form.length && $form[0]) {
            $form[0].reset();
            $form.find('input[name="_method"]').remove();
            $form.attr('action', '#');
            $form.find('select').val('').trigger('change');
            $form.find('input[data-dropzone-remove-flag="true"]').val('0');
        }

        $(this).find('.dropzone-upload').each(function () {
            resetDropzoneState(this);
        });
        $('body').focus();
        $(this).blur();
    });
});

function reloadDataTable(tableReference) {
    if (!tableReference) {
        return;
    }

    const selector = tableReference.startsWith('#') || tableReference.startsWith('.')
        ? tableReference
        : `#${tableReference}`;

    const $table = $(selector);

    if ($table.length && $.fn.DataTable.isDataTable($table)) {
        $table.DataTable().ajax.reload();
    }
}

const dropzoneInstances = new Map();

function getDropzoneInstance(dropzoneElement) {
    return dropzoneInstances.get(dropzoneElement) || null;
}

function resetDropzoneState(dropzoneElement) {
    const instance = getDropzoneInstance(dropzoneElement);

    if (!instance) {
        return;
    }

    instance.removeAllFiles(true);
    delete instance.existingMockFile;
}

function setDropzoneExistingFile(dropzoneElement, fileConfig) {
    const instance = getDropzoneInstance(dropzoneElement);

    if (!instance || !fileConfig || !fileConfig.url) {
        return;
    }

    resetDropzoneState(dropzoneElement);

    const mockFile = {
        name: fileConfig.name || 'Current Image',
        size: fileConfig.size || 1234,
        accepted: true,
        status: Dropzone.SUCCESS,
        isExistingFile: true
    };

    instance.emit('addedfile', mockFile);
    instance.emit('thumbnail', mockFile, fileConfig.url);
    instance.emit('success', mockFile);
    instance.emit('complete', mockFile);
    mockFile.previewElement.classList.add('dz-success', 'dz-complete');
    instance.files.push(mockFile);
    instance.existingMockFile = mockFile;
}

function initializeDropzones() {
    if (typeof Dropzone === "undefined") {
        return;
    }

    const dropzoneElements = document.querySelectorAll(".dropzone-upload");

    if (!dropzoneElements.length) {
        return;
    }

    // previewTemplate: Updated Dropzone default previewTemplate
    // ! Don't change it unless you really know what you are doing
    const previewTemplate = `<div class="dz-preview dz-file-preview">
        <div class="dz-details">
        <div class="dz-thumbnail">
            <img data-dz-thumbnail>
            <span class="dz-nopreview">No preview</span>
            <div class="dz-success-mark"></div>
            <div class="dz-error-mark"></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div>
            <div class="progress">
            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
            </div>
        </div>
        <div class="dz-filename" data-dz-name></div>
        <div class="dz-size" data-dz-size></div>
        </div>
        </div>`;

    Dropzone.autoDiscover = false; // Disable auto discover to prevent conflicts

    dropzoneElements.forEach(function (dropzoneElement) {
        if (dropzoneInstances.has(dropzoneElement)) {
            return;
        }

        const maxFiles = parseInt(dropzoneElement.dataset.maxFiles || "5", 10);

        const instance = new Dropzone(dropzoneElement, {
            url: "#", // This won't be used since we're submitting via form
            autoProcessQueue: false, // Prevent auto upload
            uploadMultiple: maxFiles > 1,
            parallelUploads: maxFiles,
            maxFiles: maxFiles,
            maxFilesize: 25,
            acceptedFiles: dropzoneElement.dataset.acceptedFiles || "image/*",
            addRemoveLinks: true,
            previewTemplate: previewTemplate,

            init: function() {
                const dz = this;

                // Show success tick immediately when file is added
                this.on("addedfile", function(file) {
                    if (maxFiles === 1 && this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }

                    // Add a small delay to ensure thumbnail is loaded
                    setTimeout(() => {
                        file.previewElement.classList.add("dz-success");

                        // Add animation to success mark
                        const successMark = file.previewElement.querySelector('.dz-success-mark');
                        if (successMark) {
                            successMark.style.opacity = '0';
                            successMark.style.transform = 'scale(0.5)';

                            // Animate in
                            setTimeout(() => {
                                successMark.style.transition = 'all 0.3s ease';
                                successMark.style.opacity = '1';
                                successMark.style.transform = 'scale(1)';
                            }, 100);
                        }
                    }, 300);
                });

                this.on("removedfile", function(file) {
                    if (!file.isExistingFile) {
                        return;
                    }

                    const removeFlagName = dz.element.dataset.removeFlagInput;
                    if (!removeFlagName) {
                        return;
                    }

                    const removeInput = dz.element
                        .closest('form')
                        ?.querySelector(`[name="${removeFlagName}"]`);

                    if (removeInput) {
                        removeInput.value = '1';
                    }
                });
            }
        });

        dropzoneInstances.set(dropzoneElement, instance);
    });
}

$(document).ready(function () {
    initializeDropzones();
});

// Handle AJAX form submission
$(document).on("submit", "form.ajax-form", function (e) {
    e.preventDefault();
    let form = $(this);
    let modal = form.closest(".modal");
    let tableId = form.data("datatable");
    let formData = new FormData(this);

    form.find('input[name="submit"]').prop('disabled', true);

    let validatorFn = form.data("validator");
    if (validatorFn && typeof window[validatorFn] === "function") {
        if (!window[validatorFn](form)) {
            form.find('input[name="submit"]').prop('disabled', false);
            return;
        }
    }

    const dropzoneElement = form.find(".dropzone-upload").get(0);
    if (dropzoneElement) {
        const dropzoneInstance = dropzoneInstances.get(dropzoneElement);
        const inputName = dropzoneElement.dataset.inputName || "files[]";
        const isRequired = dropzoneElement.dataset.required === "true";

        if (dropzoneInstance) {
            let files = dropzoneInstance.getAcceptedFiles().filter(function (file) {
                return !file.isExistingFile;
            });

            if (files.length > 0) {
                files.forEach(function (file) {
                    formData.append(inputName, file);
                });
            } else if (isRequired) {
                ShowToast("error", "Please add at least one file.");
                form.find('input[name="submit"]').prop('disabled', false);
                return;
            }
        } else if (isRequired) {
            ShowToast("error", "Please add at least one file.");
            form.find('input[name="submit"]').prop('disabled', false);
            return;
        }
    }

    if ($.fn.summernote) {
        form.find('.summernote-editor').each(function () {
            formData.set($(this).attr('name'), $(this).summernote('code'));
        });
    }

    // blockUI();
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // $.unblockUI();
            form[0].reset();
            $('.select2').val(null).trigger('change');
            $(form).find('textarea').val('');
            form.attr("action", '#');
            if (form.find('input[name="_method"]').length > 0) {
                form.find('input[name="_method"]').remove();
            }
            ShowToast("success", response.message);

            if (modal.length) {
                $(modal).find(':focus').blur();
                modal.modal("hide");
            }

            if (tableId) {
                reloadDataTable(tableId);
            }

            let redirectUrl = form.data("redirect");
            if (redirectUrl) {
                setTimeout(function () {
                    window.location.href = redirectUrl;
                }, 1000);
            }
        },
        error: function (jqXHR) {
            // $.unblockUI();
            if (jqXHR.status === 422) {
                // $.each(jqXHR.responseJSON.errors, function (index, value) {
                //     ShowToast("error", value);
                // });
                $.each(jqXHR.responseJSON.errors, function (field, messages) {
                    // Each field can have multiple error messages (array)
                    $.each(messages, function (index, message) {
                        ShowToast("error", message);
                    });
                });
                form.find('input[name="submit"]').prop('disabled', false);
            }
            else if(jqXHR.responseJSON.message) {
                ShowToast("error", jqXHR.responseJSON.message);
                form.find('input[name="submit"]').prop('disabled', false);
            }
            else {
                ShowToast("error", "An error occurred! Please contact the administrator.");
                form.find('input[name="submit"]').prop('disabled', false);
            }
        },
    });
});

// Edit Record
$(document).on("click", ".editRow", function (e) {
    e.preventDefault();
    // blockUI();

    let rowId = $(this).data('id');
    let targetUrl = $(this).data("target-url");
    // let modalTitle = $(this).data("title") || "Edit Record";
    let modalTitle = $(this).attr('title') || "Edit Record";
    let formSelector = $(this).data("form") || "form";
    let modalSelector = $(this).data("modal") || "#addModal";
    // let fields = $(this).data("fields");
    // let formAction = $(this).data("form-action");

    $.get(targetUrl + '/' + rowId +'/edit', function (response) {
        // $.unblockUI();

        $('#modelHeading').text(modalTitle);

        let $form = $(formSelector);
        $form.attr('action', targetUrl + '/' + rowId);
        $form.find('input[data-dropzone-remove-flag="true"]').val('0');

        // if ($form.find('input[name="_method"]').length === 0) {
            $form.append('<input type="hidden" name="_method" value="PUT">');
        // }

        if (response.fields) {
            response.fields.forEach(function (field) {                
                let name = field.trim();
                let value = response.data[name];
                let $elements = $form.find(`[name="${name}"], [name="${name}[]"]`);

                $elements.each(function () {
                    let $el = $(this);
                    let type = $el.attr('type');

                    if (type === 'file') {
                        $el.attr('required', false);
                        return; // Skip file inputs
                    }

                    if ($el.is('select')) {
                        $el.val(value).trigger('change');
                    } else if ($el.is('textarea')) {
                        $el.text(value || '');
                    } else if (type === 'radio') {
                        $el.prop('checked', $el.val() == value);
                    } else if (type === 'checkbox') {
                        if (Array.isArray(value)) {
                            $el.prop('checked', value.includes($el.val()));
                        } else {
                            $el.prop('checked', $el.val() == value);
                        }
                    } else {
                        $el.val(value || '');
                    }
                });
            });
        }

        if (Array.isArray(response.dropzones)) {
            response.dropzones.forEach(function (dropzoneConfig) {
                if (!dropzoneConfig.selector) {
                    return;
                }

                const dropzoneElement = document.querySelector(dropzoneConfig.selector);

                if (!dropzoneElement) {
                    return;
                }

                resetDropzoneState(dropzoneElement);

                if (dropzoneConfig.url) {
                    setDropzoneExistingFile(dropzoneElement, {
                        name: dropzoneConfig.name || 'Current File',
                        url: dropzoneConfig.url,
                        size: dropzoneConfig.size || 1234
                    });
                }
            });
        }

        $(modalSelector).modal('show');
    });
});

// Delete Record
$(document).on("click", ".deleteRow", function (e) {
    e.preventDefault();
    let url = $(this).data("url");
    let tableId = $(this).data("table");
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
          cancelButton: 'btn btn-label-secondary waves-effect waves-light'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            // blockUI();
            $.ajax({
                url: url,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // $.unblockUI();
                    ShowToast("success", response.message);

                    if (tableId) {
                        reloadDataTable(tableId);
                    }
                },
                error: function (jqXHR) {
                    // $.unblockUI();
                    if(jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        ShowToast("error", jqXHR.responseJSON.message);
                    }
                    else {
                        ShowToast("error", "An error occurred! Please contact the administrator.");
                    }
                },
            });
        }
    });
});

// Archive Record
$(document).on("click", ".archiveQuote", function (e) {
    e.preventDefault();
    let url = $(this).data("url");
    let tableId = $(this).data("table");
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to archive this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, archive it!',
        customClass: {
          confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
          cancelButton: 'btn btn-label-secondary waves-effect waves-light'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            // blockUI();
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: "POST"
                },
                success: function (response) {
                    // $.unblockUI();
                    ShowToast("success", response.message);

                    if (tableId) {
                        reloadDataTable(tableId);
                    }
                },
                error: function (jqXHR) {
                    // $.unblockUI();
                    if(jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        ShowToast("error", jqXHR.responseJSON.message);
                    }
                    else {
                        ShowToast("error", "An error occurred! Please contact the administrator.");
                    }
                },
            });
        }
    });
});
