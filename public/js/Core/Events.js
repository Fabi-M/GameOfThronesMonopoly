/**
 * this class will handle all events, that will be added
 */
class Events {


    /** add an event to the given target element
     * call callback function, when event gets executed
     *
     * @param specificEvent // name of the event, like 'click' or 'change'
     * @param $target // jQueryObject of element that will be the target of the event
     * @param callback // function that will be called when event gets executed
     * @param dataForCallback // data that will be send to callback function
     */
    addEvent(specificEvent, $target, callback, dataForCallback = null) {
        $target.on(specificEvent, event => {
            callback(event, dataForCallback); // send the event and optional dataForCallback along
        });
    }

    addDynamicEvent(specificEvent, childSelector, callback, dataForCallback = null){
        $(document).on(specificEvent, childSelector, event => {
            callback(event, dataForCallback);
        })
    }

    /** add an event that will be executed only ONCE
     * call callback function, when event gets executed
     *
     * @param specificEvent // name of the event, like 'click' or 'change'
     * @param $target // jQueryObject of element that will be the target of the event
     * @param callback // function that will be called when event gets executed
     * @param dataForCallback // data that will be send to callback function
     */
    addEventONCE(specificEvent, $target, callback, dataForCallback = null){
        $target.one(specificEvent, event => {
            callback(event, dataForCallback); // send the event and optional dataForCallback along
        });
    }


    /**  delete specific events for the given element
     *
     * @param $target // jQueryObject of element that will be the target of the event
     * @param specificEvent // name of the event, like 'click' or 'change'
     */
    deleteSpecificEvent($target, specificEvent) {
        $target.off(specificEvent);
    }



    /** delete all events for the given element
     *
     * @param $target // jQueryObject of element that will be the target of the event
     */
    deleteAllEvents($target) {
        $target.off();
    }

}