class Dice {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('#DICEPLACEHOLDER'), this.throwDices); //Placeholder must be changed later
    }

    /**
     *
     */
    throwDices(event, data) {
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