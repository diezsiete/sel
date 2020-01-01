import $ from 'jquery';
import 'magnific-popup';
import 'magnific-popup/dist/magnific-popup.css';

import PNotify from 'pnotify/dist/es/PNotify';
import 'pnotify/dist/PNotifyBrightTheme.css';

PNotify.defaults.styling = 'bootstrap4';


const FormPopup = function (magnificPopupButtonSelector) {
    const $btn = $(magnificPopupButtonSelector);
    const magnificPopupOptions = this._magnificPopupOptions();

    this.$wrapper = $($btn.attr('href'));
    this.$form = this.$wrapper.find('form');
    this.formUrl = this.$form.data('url');

    $btn.magnificPopup(magnificPopupOptions);

    // dismiss formulario
    this.$wrapper.on('click', this._selectors.cancelButton, e => this._formClose());
    // confirm formulario
    this.$wrapper.on('click', this._selectors.confirmButton, e => this.$form.submit());

    this.$wrapper.on(
        'submit',
        'form',
        this.handleFormSubmit.bind(this)
    );
};

$.extend(FormPopup.prototype, {
    _selectors: {
        fieldChosen: '.chosen',
        cardBody: '.card-body',
        confirmButton: '.modal-confirm',
        cancelButton: '.modal-dismiss'
    },

    handleFormSubmit: function(e) {
        e.preventDefault();

        this._loadingOverlayShow();

        const formData = {};
        $.each(this.$form.serializeArray(), function(key, fieldData){
            formData[fieldData.name] = fieldData.value;
        });

        $.ajax({
            url: this.$form.data('url'),
            method: 'POST',
            data: JSON.stringify(formData),
            success: data => {
                this._formClose()
                    ._pNotifySuccess('Datos guardados exitosamente.');
            },
            error: jqXHR => {
                const errorData = JSON.parse(jqXHR.responseText);
                this._mapErrorsToForm(errorData.errors)
                    ._loadingOverlayHide();
            }
        });
    },


    _mapErrorsToForm: function(errorData) {
        this._removeFormErrors();
        this.$form.find(':input').each(function() {
            const fieldName = $(this).attr('name');
            const $wrapper = $(this).closest('.form-group');
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
            const $input = $(this);
            const fieldName = $input.attr('name');
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
                    //datos fechas asumimos usan plugin bootstrap-datepicker, asignamos fecha al widget
                    if(typeof data[fieldName] === 'string' && data[fieldName].match(/\d+-\d+-\d/)) {
                        $input.bootstrapDP('update', data[fieldName])
                    }
                }
            }
        });
        return this._updateChosen();
    },

    _removeFormErrors: function() {
        this.$form.find('.invalid-feedback').remove();
        this.$form.find(':input').removeClass('is-invalid');
        return this;
    },

    _clearForm: function() {
        this._removeFormErrors();
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
                        this.$form.data('url', this.formUrl);
                    }
                },
            }
        }
    },

    _pNotifySuccess: function(message) {
        PNotify.success({
            title: 'Exito!',
            text: message
        });
        return this;
    },
    _pNotifyDanger: function(message) {
        PNotify.error({
            title: 'Error!',
            text: message
        });
        return this;
    },

    /**
     * TODO not working
     * @returns {FormPopup}
     * @private
     */
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
});

export default FormPopup;