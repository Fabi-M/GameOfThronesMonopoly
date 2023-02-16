/**
 * @author Selina Stöcklein
 * @date 20.12.2022
 **/
class PlayField {

    constructor() {
        let events = new Events();
        events.addEvent('click', $('.playField'), this.requestCardInfo, {"this": this});
        events.addEvent('click', $('.player-card'), this.cardButons, {"this": this});
        $('#spieler-bereich-0').bind("DOMNodeInserted", event => {
            this.requestSalary();

        });
    }

    /**
     * @author Selina STöcklein
     */
    requestSalary() {
        let url = BASEPATH + "/GoSalary";
        let request = new Ajax(url, false, this.displayOverGo, this);
        request.execute();
    }

    /**
     * @author Selina STöcklein
     */
    displayOverGo(result, that) {
        let resultObj = JSON.parse(result);
        let toast = new Toast();
        toast.Heading = 'Du erhälst dein Gehalt!';
        toast.Body = 'Du hast <strong>' + resultObj['salary'] + '</strong> erhalten.';
        toast.AllowToastClose = true;
        toast.HideAfter = false;
        toast.Loader = false;
        toast.BgColor = '#03570a';
        toast.show();
        $('#currentMoney').text(resultObj['totalMoney']);
    }

    /**
     * @author Selina STöcklein
     */
    requestCardInfo(event, data) {
        // ajax
        let url = BASEPATH + "/Card/View";
        let playFieldId = $(event.currentTarget).attr('data-id');
        let request = new Ajax(url, {'playFieldId': playFieldId}, data['this'].showCardPopUp, data);
        request.execute();
    }

    /**
     * @author Selina STöcklein
     */
    showCardPopUp(html) {
        let popUp = new ModalDialog();
        popUp.destroyFooterAndHeader();
        popUp.Body = html;
        popUp.showDialog();
    }

    /**
     * @author René
     */
    cardButons(event, data) {
        // ajax
        let url = BASEPATH + "/Card/InteractionButtons";
        let playFieldId = 1;
        let request = new Ajax(url, {'playFieldId': playFieldId}, data['this'].showCardPopUp, data);
        request.execute();
    }
}