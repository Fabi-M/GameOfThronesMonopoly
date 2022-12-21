class Figure {
    #$element;

    constructor($element) {
        this.#$element = $element;
    }

    /**
     * @author Selina Stöcklein
     * @param targetPlayFieldId
     */
    move(targetPlayFieldId) {
        let element = $(this.#$element).detach();
        $('#spieler-bereich-' + targetPlayFieldId).append(element);
    }

    /**
     * @author Selina Stöcklein
     * @param targetPlayFieldId
     */
    payRent(targetPlayFieldId) {
        let url = BASEPATH + '/Street/Rent/Pay';
        let request = new Ajax(url, {"playFieldId": targetPlayFieldId}, this.showResult, false);
        request.execute();
    }

    showResult(result) {
        //TODO 21.12.2022 Selina: anzeigen ob rent gezahlt wurde, money aktualisieren, gamoover anzeigen
        console.log(result);
    }
}