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
        $('#jailDice').attr("disabled", true);
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
        if(resultObj['error'] !== undefined){
            let toast = new Toast("Du hast bereits 3x gewürfelt und musst nun 50$ zahlen um das Gefängnis verlassen zu können","Gefängnis");
            toast.show();
        }
        else if(resultObj["inJail"] == false || resultObj["inJail"] == 0){
            $('#currentMoney').text(resultObj['money']);
            let toast = new Toast("Du hast das Gefängnis verlassen","Gefängnis");
            toast.show();
        }else{
            let toast = new Toast("Kein Pasch! Du bist immer noch im Gefängnis","Gefängnis");
            toast.show();
        }
        Jail.UpdateJailButtons(resultObj);
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

    static UpdateJailButtons(result){
        console.log(result);
            if(result["inJail"] == true || result["inJail"] == 1){
                console.log(1);
                $('.normalbuttons').attr("hidden", true);
                $('.jailbuttons').removeAttr("hidden");
                if(result["canRollForEscape"] == 0){
                    $('#jailDice').attr("disabled", true);
                }
            }else{
                console.log(2);
                $('.normalbuttons').removeAttr("hidden");
                $('.jailbuttons').attr("hidden", true);
            }
    }
}