/**
 * @author Selina Stöcklein
 * @date 21.12.2022
 **/
class Toast {


    _body = 'test';
    _heading = 'test';
    _icon = ''; // info, warning, error, success
    _loader = true;
    _loaderBg = '#fff';
    _showHideTransition = 'slide'; // fade, slide, plain
    _bgColor = '#25292e';
    _textColor = '#fff';
    _allowToastClose = true;
    _hideAfter = 5000;
    _stack = 5;
    _textAlign = 'left';
    _position = 'top-right'


    set Body(value) {
        this._body = value;
    }

    set Heading(value) {
        this._heading = value;
    }

    set Icon(value) {
        this._icon = value;
    }

    set Loader(value) {
        this._loader = value;
    }

    set LoaderBg(value) {
        this._loaderBg = value;
    }

    set ShowHideTransition(value) {
        this._showHideTransition = value;
    }

    set BgColor(value) {
        this._bgColor = value;
    }

    set TextColor(value) {
        this._textColor = value;
    }

    set AllowToastClose(value) {
        this._allowToastClose = value;
    }

    set HideAfter(value) {
        this._hideAfter = value;
    }

    set Stack(value) {
        this._stack = value;
    }

    set TextAlign(value) {
        this._textAlign = value;
    }

    set Position(value) {
        this._position = value;
    }

    set Title(title) {
        this.title = title;
    }


    constructor(body, heading) {
        this._body = body;
        this._heading = heading;
    }

    show() {
        $.toast({
            heading: this._heading,
            text: this._body,
            icon: this._icon,
            loader: this._loader,        // Change it to false to disable loader
            loaderBg: this._loaderBg,  // To change the background
            showHideTransition: this._showHideTransition,  // It can be plain, fade or slide
            bgColor: this._bgColor,              // Background color for toast
            textColor: this._textColor,            // text color
            allowToastClose: this._allowToastClose,       // Show the close button or not
            hideAfter: this._hideAfter,              // `false` to make it sticky or time in miliseconds to hide after
            stack: this._stack,                     // `false` to show one stack at a time count showing the number of toasts that can be shown at once
            textAlign: this._textAlign,            // Alignment of text i.e. left, right, center
            position: this._position       // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values to position the toast on page
        })

    }

}