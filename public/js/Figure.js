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
        let request = new Ajax(
            url,
            {"playFieldId": targetPlayFieldId},
            this.showResult,
            {'this': this});
        request.execute();
    }

    toastRent(result) {
        let resultObj = JSON.parse(result);
        console.log(resultObj);
        if (resultObj['payedRent'] > 0) {
            let toast = new Toast();
            toast.Heading = 'Es ist Miete zu zahlen!';
            let body = 'Du hast <strong>' + resultObj['payedRent'] + '</strong> gezahlt.';
            if (resultObj['isGameOver'] === 'true') {
                body += ' Du bist pleite gegangen... GAMEOVER';
            }
            toast.Body = body;
            toast.show();
        }
    }

    showResult(result, data) {
        //TODO 21.12.2022 Selina: anzeigen ob rent gezahlt wurde, money aktualisieren, gamoover anzeigen
        console.log(result);
        console.log(data);
        data['this'].toastRent(result);
    }
}