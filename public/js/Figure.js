class Figure {
    #$element;

    constructor($element) {
        this.#$element = $element;
    }

    /**
     * @author Selina Stöcklein
     * @param targetPlayFieldId
     * @param isNotPasch
     */
    move(targetPlayFieldId, isNotPasch) {
        let oldPlayFieldId = $(this.#$element).parent().parent().attr('data-id');
        let id = $(this.#$element).attr('id');
        // + 1 on targetField, otherwise the animation would stop one field before the real target field
        if (targetPlayFieldId < 39) { // else it would be buggy
            targetPlayFieldId++;
        }

        this.moveAnimate(id, oldPlayFieldId, targetPlayFieldId, this.moveAnimate, isNotPasch)
    }

    /**
     * move player recursively from playfield to playfield
     * @param id
     * @param oldPlayFieldId
     * @param targetPlayFieldId
     * @param recursiveCallback
     * @param isNotPasch
     */
    moveAnimate(id, oldPlayFieldId, targetPlayFieldId, recursiveCallback, isNotPasch) {
        let playerFigure = $('#' + id);
        let targetPlayField = $('#spieler-bereich-' + oldPlayFieldId);
        let oldOffset = playerFigure.offset();
        playerFigure.appendTo(targetPlayField);
        let newOffset = playerFigure.offset();
        let temp = playerFigure.clone().appendTo('body');
        playerFigure.hide();
        temp.css({
            'position': 'absolute',
            'left': oldOffset.left,
            'top': oldOffset.top,
            'z-index': 1000
        });

        temp.animate({'top': newOffset.top, 'left': newOffset.left}, 500, function () {
            playerFigure.show();
            temp.remove();
            oldPlayFieldId++;
            //recursive
            if (oldPlayFieldId > 39) {
                oldPlayFieldId = 0;
            }
            if (oldPlayFieldId !== targetPlayFieldId) {
                recursiveCallback(id, oldPlayFieldId, targetPlayFieldId, recursiveCallback, isNotPasch);
            } else {
                $(".diceButton").prop("disabled", isNotPasch);
                $("#next_player").prop("disabled", !isNotPasch);
            }
        });
    }

    /**
     * @author Selina Stöcklein
     * @param targetPlayFieldId
     */
    payRent(targetPlayFieldId) {
        let url = BASEPATH + '/Street/Rent/Pay';
        let request = new Ajax(
            url,
            {"playFieldId": targetPlayFieldId},
            this.showResult,
            {'this': this});
        request.execute();
    }

    /**
     * @author Selina Stöcklein
     * @param result
     */
    toastRent(result) {
        let resultObj = JSON.parse(result);
        console.log('toastRent')
        console.log(resultObj);
        $('#currentMoney').text(resultObj['totalMoney']);
        if (resultObj['payedRent'] > 0) {
            let toast = new Toast();
            toast.Heading = 'Es ist Miete zu zahlen!';
            let body = 'Du hast <strong>' + resultObj['payedRent'] + '</strong> gezahlt.';
            if (resultObj['isGameOver'] === 'true') {
                body += ' Du bist pleite gegangen... GAMEOVER';
            }
            toast.Body = body;
            toast.AllowToastClose = true;
            toast.HideAfter = false;
            toast.Loader = false;
            toast.BgColor = '#57031e';
            toast.show();
        }
    }

    /**
     * @author Selina Stöcklein
     * @param result
     * @param data
     */
    showResult(result, data) {
        let parsed = JSON.parse(result);
        if (parsed.isGameOver === true) {
            let score = new Score();
            score.requestScore();
        } else {
            data['this'].toastRent(result);
        }
    }
}