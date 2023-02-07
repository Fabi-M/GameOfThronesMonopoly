class Figure {
    #$element;

    constructor($element) {
        this.#$element = $element;
    }

    /**
     * @author Selina Stöcklein
     * @param targetPlayFieldId
     */
    move(targetPlayFieldId) {
        //let element = $(this.#$element).detach();
        // $('#spieler-bereich-' + targetPlayFieldId).append(element);
        let oldPlayFieldId = $(this.#$element).parent().parent().attr('data-id');
        oldPlayFieldId++;
        // x++
        // if x>limit x=0
        // if x==target break;
        let id = $(this.#$element).attr('id');
        for (oldPlayFieldId; oldPlayFieldId !== targetPlayFieldId; oldPlayFieldId++) {
            if (oldPlayFieldId > 39) {
                oldPlayFieldId = 0;
            }
            //move
            this.moveAnimate(id, oldPlayFieldId, targetPlayFieldId, this.moveAnimate)
            console.log(oldPlayFieldId)
        }
    }

    moveAnimate(id, oldPlayFieldId, targetPlayFieldId, lambda) {
        let element = $('#' + id);
        let newParent = $('#spieler-bereich-' + oldPlayFieldId);
        let oldOffset = element.offset();
        element.appendTo(newParent);
        let newOffset = element.offset();
        let temp = element.clone().appendTo('body');
        element.hide();
        temp.css({
            'position': 'absolute',
            'left': oldOffset.left,
            'top': oldOffset.top,
            'z-index': 1000
        });

        temp.animate({'top': newOffset.top, 'left': newOffset.left}, 500, function () {
            element.show();
            temp.remove();
            oldPlayFieldId++;
            if (oldPlayFieldId > 39) {
                oldPlayFieldId = 0;
            }
            //recursive
            if (oldPlayFieldId!== targetPlayFieldId){
                //move
                lambda(id, oldPlayFieldId, targetPlayFieldId, lambda);
            }

        });
        //await new Promise(r => setTimeout(r, 500));
        console.log('next');
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
        console.log(resultObj);
        if (resultObj['payedRent'] > 0) {
            let toast = new Toast();
            toast.Heading = 'Es ist Miete zu zahlen!';
            let body = 'Du hast <strong>' + resultObj['payedRent'] + '</strong> gezahlt.';
            if (resultObj['isGameOver'] === 'true') {
                body += ' Du bist pleite gegangen... GAMEOVER';
            }
            toast.Body = body;
            toast.show();
        }
    }

    /**
     * @author Selina Stöcklein
     * @param result
     * @param data
     */
    showResult(result, data) {
        data['this'].toastRent(result);
    }
}