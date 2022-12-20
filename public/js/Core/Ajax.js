class Ajax {

    //Class Variables

    type = 'POST';

    url = ''

    successFunction;

    successFunctionOption;

    requestData = {};

    response = '';

    options = {};

    /**
     * Set passed values as class variables
     * @param url
     * @param data
     * @param successFunction
     * @param successFunctionOption
     * @param file
     * @param options
     */
    constructor(url, data, successFunction, successFunctionOption = false, options = false, file = false) {
        this.url = url;
        this.requestData = data;
        this.successFunction = successFunction;
        this.successFunctionOption = successFunctionOption;

        if (options !== false) {
            this.options = options
        }
        this.file = file;
    }


    /**
     * Set the class variables in an object, which is also a Class Variable
     * If this.file is undefind or this.requestData is an instance of FormData set cache, contentType and processData to false
     * Make a Ajax Call with this.options. Depending on whether it fails or not and whether there is a return, the function to display a respective message is called
     */
    execute() {
        this.options.type = this.type;
        this.options.url = this.url;
        this.options.data = this.requestData;
        //this.options.data = JSON.stringify(this.requestData);
        this.options.context = this;

        if (this.file !== undefined || this.requestData instanceof FormData) {
            this.options.cache = false;
        }
        $.ajax(this.options)
            .done(function (response) {
                if (response === undefined && response === '') {
                    this.showMessage('Empty response');
                    return;
                }
                this.success(response);
            })
            .fail(function (foo) {
                this.showMessage('HTTP ERROR, something went wrong in your ajax');
            });
    }

    /**
     * Calls a URL and send data there
     * @param $element
     * @param data
     */
    loadContent($element, data = false) {
        $element.load(this.url, data);
    }

    /**
     * Function is called if the Ajax Call passed and has a response
     * If response.massage is defined than the function showMessage is called with the parameter response.massage
     * If response.Massage is defined than the function showMessage is called with the parameter response.Massage
     * If this.successFunction is defined than the this.successFunction is called with the parameter response and this.successFunctionOption
     * If
     * @param response
     */
    success(response) {
        this.response = response;
        if (response.message !== undefined) {
            this.showMessage(response.message);
        }
        if (response.Message !== undefined) {
            this.showMessage(response.Message);
        }

        if (this.successFunction !== undefined) {
            this.successFunction(response, this.successFunctionOption);
        }
    }

    /**
     * Call a Function to show a Display on the Modal
     * @param message
     */
    showMessage(message) {
        var dialog = new ModalDialog();
        dialog.body = message;
        dialog.showDialog();
    }

    /**
     * Extends the object
     * @param ident
     * @param value
     */
    addOption(ident, value) {
        this.options[ident] = value;
    }
}