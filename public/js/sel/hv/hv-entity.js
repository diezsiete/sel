'use strict';

(function(window, $) {
    window.hvEntity = function ($wrapper, $wrapperConfirm, dataTable) {
        this.$wrapper = $wrapper;
        this.$wrapperConfirm = $wrapperConfirm;
        this.$form = this.$wrapper.find(this._selectors.formEntity);
        this.$formUrl = this.$form.data('url');
        this.dataTable = dataTable;

        $(this._selectors.fieldChosen).chosen({
            width: '100%'
        });

        const tbody = this.dataTable.table().body();

        const magnificPopupOptions = this._magnificPopupOptions();
        $('.crear-entity').magnificPopup(magnificPopupOptions);

        magnificPopupOptions.delegate = 'a.modal-with-form';
        $(tbody).magnificPopup(magnificPopupOptions);

        // dismiss formulario
        $(document).on('click', '.modal-dismiss', e => this._formClose());
        // confirm formulario
        this.$wrapper.on('click', this._selectors.confirmButton, e => this.$form.submit());

        this.$wrapperConfirm.on('click', this._selectors.confirmButton, e => this._delete($(e.currentTarget)));

        this.$wrapper.on(
            'submit',
            this._selectors.formEntity,
            this.handleFormSubmit.bind(this)
        );

    };

    $.extend(window.hvEntity.prototype, {
        _selectors: {
            formEntity: '.form-entity',
            fieldChosen: '.chosen',
            cardBody: '.card-body',
            confirmButton: '.modal-confirm'
        },

        handleFormSubmit: function(e) {
            e.preventDefault();

            this._loadingOverlayShow();

            var formData = {};
            $.each(this.$form.serializeArray(), function(key, fieldData){
                formData[fieldData.name] = fieldData.value;
            });

            $.ajax({
                url: this.$form.data('url'),
                method: 'POST',
                data: JSON.stringify(formData),
                success: data => {
                    this._drawDatatable()
                        ._formClose()
                        ._pNotifySuccess('Datos guardados exitosamente.');
                },
                error: jqXHR => {
                    var errorData = JSON.parse(jqXHR.responseText);
                    this._mapErrorsToForm(errorData.errors)
                        ._loadingOverlayHide();
                }
            });
        },


        _mapErrorsToForm: function(errorData) {
            this._removeFormErrors();
            this.$form.find(':input').each(function() {
                var fieldName = $(this).attr('name');
                var $wrapper = $(this).closest('.form-group');
                if (!errorData[fieldName]) {
                    // no error!
                    return;
                }
                var $error = $(
                    '<span class="invalid-feedback d-block"><span class="d-block">'+
                    '<span class="form-error-icon badge badge-danger text-uppercase">Error</span> '+
                    '<span class="form-error-message">'+errorData[fieldName]+'</span>'
                    +'</span></span>');
                $(this).addClass('is-invalid');
                $wrapper.append($error);
            });
            return this;
        },

        _mapEntityToForm: function(data) {
            this.$form.find(':input').each(function() {
                var $input = $(this);
                var fieldName = $input.attr('name');
                if (!data[fieldName]) {
                    // no data!
                    return;
                }
                if($input.attr('type') === 'checkbox') {
                    $input.prop('checked', data[fieldName]);
                } else {
                    if(typeof data[fieldName] === 'object') {
                        $input.val(data[fieldName].id);
                    } else {
                        $input.val(data[fieldName]);
                    }
                }
            });
            return this._updateChosen();
        },

        _removeFormErrors: function() {
            this.$form.find('.invalid-feedback').remove();
            return this;
        },

        _clearForm: function() {
            this._removeFormErrors();
            this.$form.find(':input').removeClass('is-invalid');
            this.$form[0].reset();
            return this._updateChosen();
        },

        _magnificPopupOptions: function () {
            return {
                type: 'inline',
                preloader: false,
                focus: '#name',
                modal: true,
                callbacks: {
                    // When elemened is focused, some mobile browsers in some cases zoom in
                    // It looks not nice, so we disable it:
                    beforeOpen: function (e) {
                        this.st.focus = $(window).width() < 700 ? false : '#name';
                    },
                    elementParse: item => {
                        const deleteUrl = item.el.data('delete-url');
                        if(deleteUrl) {
                            this.$wrapperConfirm.find(this._selectors.confirmButton).data('delete-url', deleteUrl);
                        } else {
                            const getUrl = item.el.data('get-url');
                            if (getUrl) {
                                this.$form.data('url', item.el.data('update-url'));
                                this._loadingOverlayShow();
                                $.ajax({
                                    url: getUrl,
                                    method: 'GET',
                                    success: data => {
                                        this._mapEntityToForm(data)
                                            ._loadingOverlayHide();
                                    },
                                    error: jqXHR => {
                                        this._formClose()
                                            ._pNotifyDanger(jqXHR.responseText);
                                    }
                                });
                            } else {
                                this.$form.data('url', this.$formUrl);
                            }
                        }

                    },
                }
            }
        },

        _delete: function($target) {
            const deleteUrl = $target.data('delete-url');
            $.ajax({
                url: deleteUrl,
                success: data => {
                    this._drawDatatable()._pNotifySuccess("Registro borrado exitosamente")._popupClose();
                },
                error: jqXHR => {
                    this._pNotifyDanger(jqXHR.responseText)._popupClose();
                }
            });
        },
        _pNotifySuccess: function(message) {
            new PNotify({
                title: 'Exito!',
                text: message,
                type: 'success'
            });
            return this;
        },
        _pNotifyDanger: function(message) {
            new PNotify({
                title: 'Error!',
                text: message,
                type: 'danger'
            });
            return this;
        },

        _loadingOverlayShow: function() {
            this.$wrapper.find(this._selectors.cardBody).trigger('loading-overlay:show');
            return this;
        },
        _loadingOverlayHide: function() {
            this.$wrapper.find(this._selectors.cardBody).trigger('loading-overlay:hide');
            return this;
        },
        _popupClose: function(){
            $.magnificPopup.close();
            return this;
        },
        _formClose: function() {
            return this._popupClose()._clearForm()._loadingOverlayHide();
        },
        _updateChosen: function() {
            this.$wrapper.find(this._selectors.fieldChosen).trigger("chosen:updated");
            return this;
        },
        _drawDatatable: function() {
            this.dataTable.draw();
            return this;
        }
    });
})(window, jQuery);