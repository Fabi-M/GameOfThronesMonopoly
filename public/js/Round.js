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
        let parsedJSON = JSON.parse(data);
        console.log(parsedJSON);
        if(!parsedJSON["success"]){
            console.log("error");
            return;
            // todo: add error handling frontend
        }
        let toast = new Toast("Spieler "+parsedJSON["ingameId"]+" ist nun am Zug!","Zug beendet");
        toast.show();
        //TODO 09.02.2023 Selina: in updateUi Methode auslagern
        $('div.spieleranzeige').text('Spieler am Zug: ' + parsedJSON['ingameId']);
        $('#currentMoney').text(parsedJSON['money']);
        $('#ownedStreets').html(parsedJSON['streets']);
        $( "#next_player" ).prop( "disabled", true );
        $( "#wuerfeln" ).prop( "disabled", false );
    }
}