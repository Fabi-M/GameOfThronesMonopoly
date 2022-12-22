class Round {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('#next_player'), this.endRound, {"this": this});
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
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
     * @author Selina Stöcklein & Christian Teubner
     * @param data
     */
    displayNextPlayerPopup(data) {
        $('div.spieleranzeige').text('Spieler am Zug: ' + data);
        $( "#next_player" ).prop( "disabled", true );
        $( "#wuerfeln" ).prop( "disabled", false );
    }
}