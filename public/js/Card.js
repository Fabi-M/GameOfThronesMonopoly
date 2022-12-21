class Card {
    constructor() {
        let events = new Events();
        //TODO 15.12.2022 Selina: placeholder austauschen
        events.addEvent('click', $('#buyStreet'), this.buyCard, {'this':this});
        events.addEvent('click', $('#verkaufen'), this.sellCard, {'this':this});
        events.addEvent('click', $('#haus_kaufen'), this.buyHouse, {'this':this});
        events.addEvent('click', $('#haus_verkaufen'), this.sellHouse, {'this':this});
        events.addEvent('click', $('#VIEWCARDPLACEHOLDER'), this.viewCard,{'this':this}); //Placeholder must be changed later
        events.addEvent('click', $('#MORTGAGECARDPLACEHOLDER'), this.mortgageCard,{'this':this}); //Placeholder must be changed later
    }

    /**
     * @author Selina Stöcklein
     * @param event
     * @param data
     */
    buyCard(event, data) {
        let that = data['this'];
        let url = BASEPATH + '/Street/Buy';
        let request = new Ajax(url, false, that.displayBuyResult, data);
        request.execute();

    }

    /**
     * @author Fabian Müller
     * @param event
     * @param data
     */
    sellCard(event, data) {
        let that = data['this'];
        let id = 1; // todo sobald card popup fertig, id davon holen
        let url = BASEPATH + '/Street/Sell/'+id;
        let request = new Ajax(url, false, that.displaySellResult, data);
        request.execute();
    }

    /**
     * @author Fabian Müller
     * @param event
     * @param data
     */
    buyHouse(event, data) {
        let that = data['this'];
        let id = 1; // todo sobald card popup fertig, id davon holen
        let url = BASEPATH + '/Street/House/Buy/'+id;
        let request = new Ajax(url, false, that.displayBuyResult, data);
        request.execute();

    }

    /**
     * @author Fabian Müller
     * @param event
     * @param data
     */
    sellHouse(event, data) {
        let that = data['this'];
        let id = 1; // todo sobald card popup fertig, id davon holen
        let url = BASEPATH + '/Street/House/Sell/'+id;
        let request = new Ajax(url, false, that.displaySellResult, data);
        request.execute();
    }

    /**
     * @author Selina Stöcklein
     * @param data
     */
    displayBuyResult(data) {
        console.log(data);
        // Spieler anzeigen, ob Straße gekauft wurde oder nicht
        // Money aktualisieren, eventuelle Fehler anzeigen, etc.
    }

    /**
     * @author Fabian Müller
     * @param data
     */
    displaySellResult(data) {
        console.log(data);
        // Spieler anzeigen, ob Straße verkauft wurde oder nicht
        // Money aktualisieren, eventuelle Fehler anzeigen, etc.
    }

    /**
     * @author Selina Stöcklein
     */
    viewCard(event, data) {
        //TODO 15.12.2022 Selina: playfield_id von der angeklickten karte bekommen
        let that = data['this'];
        //TODO 15.12.2022 Selina: Jedes Spielfeld soll eine "data-playfield_id=..." haben,
        // die dann für requests verwendet werden kann
        let playfieldId = $('.dice').attr('data-playfieldId');
        let url = BASEPATH + '/Card/View'; // post playfield_id
        let request = new Ajax(url, {'playfield_id' : playfieldId}, that.displayCardPopup, data);
        request.execute();
    }

    /**
     * @author Selina Stöcklein
     * @param data
     */
    displayCardPopup(data) {
        //TODO 15.12.2022 Selina: DialogModal erstellen mit Content:
        // Bild der Karte + dazugehörige Buttons (wie "Haus bauen", "Straße verkaufen", etc)
    }


    /**
     * @author Selina Stöcklein
     * @param event
     * @param data
     */
    mortgageCard(event, data) {
        let that = data['this'];
        let url = BASEPATH + '/Street/Mortgage'; // post playfield_id
        let request = new Ajax(url, {'playfield_id' : that}, that.displayMortgagePopup, data);
        request.execute();
    }

    displayMortgagePopup() {

    }
}