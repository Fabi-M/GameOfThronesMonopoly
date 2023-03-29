class Card {
    constructor() {
        let events = new Events();
        //TODO 15.12.2022 Selina: placeholder austauschen
        events.addDynamicEvent('click', '#buyStreet', this.buyCard, {'this': this});
        events.addDynamicEvent('click', '#verkaufen', this.sellCard, {'this': this});
        events.addDynamicEvent('click', '#haus_kaufen', this.buyHouse, {'this': this});
        events.addDynamicEvent('click', '#haus_verkaufen', this.sellHouse, {'this': this});
    }

    /**
     * @author Selina Stöcklein
     * @param event
     * @param data
     */
    buyCard(event, data) {
        let that = data['this'];
        let url = BASEPATH + '/Street/Buy';
        let request = new Ajax(url, false, that.displayStreetBuyResult, data);
        request.execute();

    }

    /**
     * @author Fabian Müller
     * @param event
     * @param data
     */
    sellCard(event, data) {
        let that = data['this'];
        let id = $(event["target"]).attr("data-value");
        let url = BASEPATH + '/Street/Sell/' + id;
        let request = new Ajax(url, false, that.displayStreetSellResult, data);
        request.execute();
    }

    /**
     * @author Fabian Müller
     * @param event
     * @param data
     */
    buyHouse(event, data) {
        let that = data['this'];
        let id = $(event["target"]).attr("data-value");
        let url = BASEPATH + '/Street/House/Buy/' + id;
        let request = new Ajax(url, false, that.displayHouseBuyResult, data);
        request.execute();

    }

    /**
     * @author Fabian Müller
     * @param event
     * @param data
     */
    sellHouse(event, data) {
        let that = data['this'];
        let id = $(event["target"]).attr("data-value");
        let url = BASEPATH + '/Street/House/Sell/' + id;
        let request = new Ajax(url, false, that.displayHouseSellResult, data);
        request.execute();
    }

    /**
     * @author Selina Stöcklein , Fabian Müller
     * @param data
     */
    displayStreetBuyResult(data) {
        data = JSON.parse(data);
        if (!data["success"]) {
            console.log(data);
            console.log("error");
            return;
            // todo: add error handling frontend
        }
        let toast = new Toast("Du hast die Straße " + data["streetName"] + " gekauft", "Straße gekauft");
        toast.show();
        // add pennant
        $('#pennant-' + data["playFieldId"]).addClass('pennant-owner-' + data["inGamePlayerId"]);
        // Money aktualisieren, eventuelle Fehler anzeigen, etc.
        $('#currentMoney').text(data['totalMoney']);
    }

    /**
     * @author Fabian Müller
     * @param data
     */
    displayStreetSellResult(data) {
        data = JSON.parse(data);
        if (!data["success"]) {
            console.log(data);
            console.log("error");
            return;
            // todo: add error handling frontend
        }
        let toast = new Toast("Du hast die Straße " + data["streetName"] + " verkauft", "Straße verkauft");
        toast.show();
        // remove pennant
        $('#pennant-' + data["playFieldId"]).removeClass('pennant-owner-' + data["inGamePlayerId"]);
        // Money aktualisieren, eventuelle Fehler anzeigen, etc.
        $('#currentMoney').text(data['totalMoney']);
    }

    /**
     * @author Fabian Müller
     * @param data
     * @param classInstance
     */
    displayHouseBuyResult(data, classInstance) {
        data = JSON.parse(data);
        if (!data["success"]) {
            console.log(data);
            console.log("error");
            return;
            // todo: add error handling frontend
        }
        let toast = new Toast("Du hast ein Haus auf der Straße " + data["streetName"] + " gekauft", "Haus gekauft");
        toast.show();
        classInstance['this'].addHouse(data["position"], data["buildings"]);

        // Money aktualisieren, eventuelle Fehler anzeigen, etc.
        $('#currentMoney').text(data['totalMoney']);
    }

    /**
     * @author Fabian Müller
     * @param data
     * @param classInstance
     */
    displayHouseSellResult(data, classInstance) {
        data = JSON.parse(data);
        if (!data["success"]) {
            console.log(data);
            console.log("error");
            return;
            // todo: add error handling frontend
        }
        let toast = new Toast("Du hast ein Haus auf der Straße " + data["streetName"] + " verkauft", "Haus verkauft");
        toast.show();

        classInstance['this'].removeHouse(data["position"], data["buildings"]);
        // Money aktualisieren, eventuelle Fehler anzeigen, etc.
        $('#currentMoney').text(data['totalMoney']);

    }

    /**
     * @author Selina Stöcklein
     */
    viewCard(event, data) {
        let that = data['this'];
        let playfieldId = $('.diceButton').attr('data-playfieldId');
        let url = BASEPATH + '/Card/View'; // post playfield_id
        let request = new Ajax(url, {'playfield_id': playfieldId}, that.displayCardPopup, data);
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
        let request = new Ajax(url, {'playfield_id': that}, that.displayMortgagePopup, data);
        request.execute();
    }

    displayMortgagePopup() {

    }

    /**
     * Display a new house on the street
     * @author Fabian Müller
     * @param id
     * @param count
     */
    addHouse(id, count) {
        let path = IMGPATH + "/House.png";
        if (count === 5) {
            for (let i = 0; i < 4; i++) {
                this.removeHouse(id);
            }
            path = IMGPATH + "/Hotel.png";
        }
        let element = $("#strassen-bereich-" + id);
        let className = element.attr("class");
        let deg = className.substring(className.length - 1) * 90;
        console.log(deg)
        element.append("<img class='building" + deg + "' alt='house' src=" + path + ">");
    }

    /**
     * Remove a house on the street
     * @author Fabian Müller
     * @param id
     * @param count
     */
    removeHouse(id, count) {
        $("#strassen-bereich-" + id).children().last().remove();
        if (count === 4) {
            for (let i = 0; i < 4; i++) {
                this.addHouse(id, count);
            }
        }
    }
}