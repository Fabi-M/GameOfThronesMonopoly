class Round {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('#ENDROUNDPLACEHOLDER'), this.endRound); //Placeholder must be changed later
    }

    /**
     * @author Selina Stöcklein
     * @param event
     * @param data
     */
    endRound(event, data) {

        let that = data['this'];
        let url = BASEPATH + '/EndTurn';
        let request = new Ajax(url, false, that.displayNextPlayerPopup, data);
        request.execute();
    }

    /**
     * @author Selina Stöcklein
     * @param data
     */
    displayNextPlayerPopup(data) {
        //TODO 15.12.2022 Selina: PopUp mit Info, welcher Spieler nun am Zug ist
        // + Spielstand/Inventar des nächsten Spielers anzeigen
        // eventuell spieler infos neu von PHP laden lassen?
        // (um falsche daten im Browser zu vermeiden)
    }
}