class Round {

    static INSTANCE;

    constructor() {
        Round.INSTANCE = this;
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
        Round.INSTANCE.UpdateUI(parsedJSON);
    }

    UpdateUI(nextPlayer){
        console.log(nextPlayer["inJail"]);
        if(nextPlayer["inJail"] == 1){
            console.log(1);
            $('.normalbuttons').attr("hidden", true);
            $('.jailbuttons').removeAttr("hidden");
        }else{
            console.log(2);
            $('.normalbuttons').removeAttr("hidden");
            $('.jailbuttons').attr("hidden", true);
        }

        $('div.spieleranzeige').text('Spieler am Zug: ' + nextPlayer['ingameId']);
        $('#currentMoney').text(nextPlayer['money']);
        $('#ownedStreets').html(nextPlayer['streets']);
        $( "#next_player" ).prop( "disabled", true );
        $( "#wuerfeln" ).prop( "disabled", false );
    }
}