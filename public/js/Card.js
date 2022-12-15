class Card {
    constructor() {
        let events = new Events();
        //TODO 15.12.2022 Selina: placeholder austauschen
        events.addEvent('click', $('#BUYCARDPLACEHOLDER'), this.buyCard); //Placeholder must be changed later
        events.addEvent('click', $('#VIEWCARDPLACEHOLDER'), this.viewCard); //Placeholder must be changed later
        events.addEvent('click', $('#TRADECARDPLACEHOLDER'), this.tradeCard); //Placeholder must be changed later
        events.addEvent('click', $('#MORTGAGECARDPLACEHOLDER'), this.mortgageCard); //Placeholder must be changed later
    }

    /**
     *
     */
    buyCard(event, data) {
        let that = data['this'];
        let url = BASEPATH + '/street/buy';
        let request = new Ajax(url, false, that.displayBuyResult, data);
        request.execute();
        event.preventDefault();
        console.log("klick");
        let valid = true;
        $('[required]').each(function () {
            if ($(this).is(':invalid') || !$(this).val()) valid = false;
        })
        if (!valid) return;
        console.log("valid");
    }

    displayBuyResult() {
        // Spieler anzeigen, ob Straße gekauft wurde oder nicht
    }

    /**
     *
     */
    viewCard(event, data) {
        event.preventDefault();
        console.log("klick");
        let valid = true;
        $('[required]').each(function () {
            if ($(this).is(':invalid') || !$(this).val()) valid = false;
        })
        if (!valid) return;
        console.log("valid");
    }

    /**
     *
     */
    tradeCard(event, data) {
        event.preventDefault();
        console.log("klick");
        let valid = true;
        $('[required]').each(function () {
            if ($(this).is(':invalid') || !$(this).val()) valid = false;
        })
        if (!valid) return;
        console.log("valid");
    }

    /**
     *
     */
    mortgageCard(event, data) {
        event.preventDefault();
        console.log("klick");
        let valid = true;
        $('[required]').each(function () {
            if ($(this).is(':invalid') || !$(this).val()) valid = false;
        })
        if (!valid) return;
        console.log("valid");
    }
}