/**
 * @author Selina Stöcklein
 * @date 20.12.2022
 **/
class PlayField {

    constructor() {
        let events = new Events();
        events.addEvent('click', $('.playField'), this.requestCardInfo, {"this": this});
        events.addEvent('click', $('.player-card'), this.cardButons, {"this": this});

    }

    requestCardInfo(event, data) {
        // ajax
        let url = BASEPATH + "/Card/View";
console.log(event)
        let playFieldId = $(event.currentTarget).attr('data-id');
console.log(playFieldId)
        let request = new Ajax(url, {'playFieldId': playFieldId}, data['this'].showCardPopUp, data);
        request.execute();
    }

    showCardPopUp(html) {
        let popUp = new ModalDialog();
        popUp.destroyFooterAndHeader();
        popUp.Body = html;
        popUp.showDialog();
    }

    cardButons(event, data) {
        // ajax
        let url = BASEPATH + "/Card/InteractionButtons";
        let playFieldId = 1;
        let request = new Ajax(url, {'playFieldId': playFieldId}, data['this'].showCardPopUp, data);
        request.execute();
    }
}