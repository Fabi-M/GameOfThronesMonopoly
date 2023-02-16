class Jail{
    constructor() {
        console.log("AHHHHHHHHH");
        let events = new Events();
        events.addEvent('click', $('#jailDice'), this.rollDices, {"this": this});
        events.addEvent('click', $('#payout'), this.payPayout, {"this": this});
    }

    /**
     *
     * @param event
     * @param data
     */
    rollDices(event, data){
        console.log("ESCAPEROLL");
        let url = BASEPATH + '/Roll/Escape';
        let request = new Ajax(url, false, data["this"].displayPopup, data);
        request?.execute();
    }

    /**
     *
     * @param result
     */
    displayPopup(result) {
        let resultObj = JSON.parse(result);
        console.log(resultObj);
        if(resultObj["escaped"] == true){
            alert("Du hast das Gefängnis verlassen");
        }else{
            alert("Du bist immer noch im Gefängnis");
        }
    }

    /**
     *
     * @param event
     * @param data
     */
    payPayout(event, data){
        console.log("ESCAPEBUYOUT");
        let url = BASEPATH + '/JailPayout';
        let request = new Ajax(url, false, data["this"].displayPopup, data);
        request?.execute();
    }
}