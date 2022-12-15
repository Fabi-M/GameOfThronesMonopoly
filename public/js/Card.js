class Card {
    constructor() {
        let events = new Events();
        //TODO 15.12.2022 Selina: placeholder austauschen
        events.addEvent('click', $('#BUYCARDPLACEHOLDER'), this.buyCard, {'this':this}); //Placeholder must be changed later
        events.addEvent('click', $('#VIEWCARDPLACEHOLDER'), this.viewCard,{'this':this}); //Placeholder must be changed later
        events.addEvent('click', $('#TRADECARDPLACEHOLDER'), this.tradeCard,{'this':this}); //Placeholder must be changed later
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
     * @author Selina Stöcklein
     * @param data
     */
    displayBuyResult(data) {
        // Spieler anzeigen, ob Straße gekauft wurde oder nicht
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