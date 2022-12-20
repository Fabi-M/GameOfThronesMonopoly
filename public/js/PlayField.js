/**
 * @author Selina Stöcklein
 * @date 20.12.2022
 **/
class PlayField {

    constructor() {
        let events = new Events();
        events.addEvent('click', $('.playField'), this.requestCardInfo, {"this": this});
    }

    requestCardInfo(event, data) {
        // ajax
        let url = BASEPATH + "/Card/View";
        let playFieldId = 1;
        let request = new Ajax(url, {'playFieldId': playFieldId}, data['this'].showCardPopUp, data);
        request.execute();
    }

    showCardPopUp(html) {
        let popUp = new ModalDialog();
        popUp.Body = html;
        popUp.showDialog();
    }
}