/**
 * @author Selina Stöcklein
 * @date 20.12.2022
 **/
class PlayField {

    constructor() {
        let events = new Events();
        events.addEvent('click', $('.playField'), this.requestCardInfo, {"this": this});
        events.addEvent('click', $('.player-card'), this.cardButons, {"this": this});
        // If a player steps on the "Über-Los"-playfield, an event will be triggert, to get the 200 $ salary!
        let x = new MutationObserver( e => {
            if (e[0].addedNodes) console.log(e);
            let $target = $(e[0].addedNodes[0]);
            if ($target.hasClass('playerFigure')) {
                this.requestSalary();
            }
        });

        x.observe($('#spieler-bereich-0')[0], {childList: true});
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
        const bannedFieldIds = [0, 2, 4, 7, 10, 17, 20, 22, 30, 33, 36, 38];
        if ($.inArray(parseInt(playFieldId), bannedFieldIds) < 0) {
            let request = new Ajax(url, {'playFieldId': playFieldId}, data['this'].showCardPopUp, data);
            request.execute();
        }
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