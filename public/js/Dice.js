class Dice {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('.dice'), this.throwDices); //Placeholder must be changed later
    }

    /**
     * @author Selina Stöcklein
     */
    throwDices(event, data) {
        let that = data['this'];
        //TODO 15.12.2022 Selina: soll je nach action Move oder Prison an URL anhängen "data-action='Move'"
        //TODO 15.12.2022 Selina: Alle buttons die wie Dice interagieren sollen brauchen die Klasse "dice"
        let action = $('.dice').attr('data-action');
        let url = BASEPATH + '/Roll/' + action;
        if(action == 'Move'){

            let request = new Ajax(url, false, that.displayPopup, data);
        }else if(action == 'Prison') {
            let request = new Ajax(url, false, that.displayPopup, data);
        }
        request.execute();
    }

    /**
     * @author Selina Stöcklein
     * @param result
     */
    displayPopup(result) {
        // result == '' wenn der spieler nur läuft brauchen wir kein popup
        if (result != "") {
            // PopUp anzeigen mit msg! Z.B. "Du kommst aus dem Gefängnis frei!"
        }
    }
}