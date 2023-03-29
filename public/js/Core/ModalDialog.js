class ModalDialog {

    //Class Variables

    title = '';

    body = '';

    successFunction = false;

    successFunctionName = 'OK';

    abortFunction = false;

    abortFunctionName = 'Abbrechen';

    destructed = false;

    /**
     * Gets a HTML element and clone it.
     * Check the Handover (If the handover is empty a random number is generated)
     * Set the id from the element to the random number
     * Set empty functions
     * If the this.element has the hidden.bs.modal attribute than the .remove function is called.
     * @param id
     */
    constructor(id = '') {
        this.element = $('#infoModal').clone();
        if (id === '') {
            id = Math.random();
        }
        this.element.attr('id', id);

        this.element.on('hidden.bs.modal', event => {
            this.element.remove();
        });
    }


    //Functions to set Class Variables

    set Title(title) {
        this.title = title;
    }

    set Body(body) {
        this.body = body;
    }

    set SuccessFunction(successFunction) {
        this.successFunction = successFunction;
    }


    set AbortFunction(abortFunction) {
        this.abortFunction = abortFunction;
    }

    set SuccessFunctionName(successFunctionName) {
        this.successFunctionName = successFunctionName;
    }

    set AbortFunctionName(abortFunctionName) {
        this.abortFunctionName = abortFunctionName;
    }

    set SuccessFunctionOptions(successFunctionOptions) {
        this.successFunctionOptions = successFunctionOptions;
    }

    set AbortFunctionOptions(abortFunctionOptions) {
        this.abortFunctionOptions = abortFunctionOptions;
    }

    destroyFooterAndHeader(){
        this.element.find('.modal-header').remove();
        this.element.find('.modal-footer').remove();
    }

    destroyHeader(){
        this.element.find('.modal-header').remove();
    }

    disableCloseButton() {
        this.element.find('.modalDialogCloseBtn').addClass('invisible');
    }

    disableYesNoButton() {
        this.element.find('.modal-button-yes').addClass('invisible');
        this.element.find('.modal-button-no').addClass('invisible');

    }
    enableYesNoButton(){
        this.element.find('.modal-button-yes').removeClass('invisible');
        this.element.find('.modal-button-no').removeClass('invisible');
    }

    /**
     *
     * @param option // true, false or static
     */
    setBackdrop(option) {
        this.element.modal({backdrop: option});
    }

    /**
     * Call a function this.element.modal with the parameter hide
     * Call a function this.element.remove
     * Call a function $('.modal-backdrop').remove
     */
    destruct() {
        console.log('stirb')
        this.element.modal('hide');
        this.element.remove();
        $('.modal-backdrop').remove();
        this.destructed = true;
    }

    /**
     * Call a function, which shows the Modal
     * Design the Modal with setting the title and body
     * Call a function if there is a click on the yes button
     * Call a function if there is a click on the no button
     */
    showDialog() {
        this.element.modal('show');
        this.element.find('.modal-title').html(this.title)
        this.element.find('.modal-body').html(this.body);
        let yesButton = this.element.find('.modal-button-yes');

        if (this.successFunction !== false) {
            yesButton.removeClass('invisible').addClass('visible');

            yesButton.click(event => {
                let result = this.successFunction(this.successFunctionOptions)
                if (result !== false) {
                    console.log('lalala')
                    this.destruct();
                }
            }).html(this.successFunctionName);
        } else {
            yesButton.addClass('invisible').removeClass('visible');

        }

        let noButton = this.element.find('.modal-button-no');
        if (this.abortFunction !== false) {
            noButton.removeClass('invisible').addClass('visible');
            noButton.click(event => {
                this.abortFunction(this.abortFunctionOptions);
                this.destruct();
                console.log('ahhhhhhhhhhh')
            }).html(this.abortFunctionName);
        } else {
            noButton.addClass('invisible').removeClass('visible');

        }
    }

    /**
     * Sets the size of the modal
     * @param size // size of the modal: sm, lg or xl
     */
    setSize(size){
        //this.element.addClass("modal-"+size);
        this.element.find(".modal-dialog").addClass("modal-"+size);
    }
}