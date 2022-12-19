class Dice {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('#wuerfeln'), this.throwDices, {"this" : this});
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
     */
    throwDices(event, data) {
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
        var resultObj = JSON.parse(result);
        alert("Deine gewürfelte Zahlen: \n" + resultObj[0] + " und " + resultObj[1]);
    }
}