class Figure {
    #$element;

    constructor($element) {
        this.#$element = $element;
    }

    move(targetPlayFieldId) {
        let element = $(this.#$element).detach();
        $('#spieler-bereich-' + targetPlayFieldId).append(element);
    }
}