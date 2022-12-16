class Dice {
    constructor() {
        console.log("Consturct");
        console.log($('.dice'));
        let events = new Events();
        events.addEvent('click', $('.dice'), this.throwDices);
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
     */
    throwDices(event, data) {
        console.log("Here");
        let that = data['this'];
        //TODO 15.12.2022 Selina: soll je nach action Move oder Prison an URL anhängen "data-action='Move'"
        //TODO 15.12.2022 Selina: Alle buttons die wie Dice interagieren sollen brauchen die Klasse "dice"
        let action = $('.dice').attr('data-action');
        let url = BASEPATH + '/Roll/' + action; // post playfield_id
        let request = new Ajax(url, {'playfield_id': that}, that.displayPopup, data);
        request.execute();
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
     * @param result
     */
    displayPopup(result) {
        // result == '' wenn der spieler nur läuft brauchen wir kein popup
        if (result != "") {
            // PopUp anzeigen mit msg! Z.B. "Du kommst aus dem Gefängnis frei!"
        }
    }
}