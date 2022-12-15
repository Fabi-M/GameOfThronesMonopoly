class Round {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('#ENDROUNDPLACEHOLDER'), this.endRound); //Placeholder must be changed later
    }

    /**
     *
     */
    endRound(event, data) {
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