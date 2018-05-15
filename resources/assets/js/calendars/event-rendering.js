/**
 * This function sets the element of a full-calendar event.
 *
 * Every time an event is rendered, this function is called.
 *
 * @param event
 * @param element
 * @param view
 */
function eventRenderer(event, element, view) {

    iconRenderer(event, element, view);
    descriptionRenderer(event, element, view);

    switch(event.type) {
        case 'person-birth-day':
            eventBirthDayRenderer(event, element, view);
            break;
    }

};

function descriptionRenderer(event, element, view) {
    if(event.description && view.constructor.name === 'AgendaView') {

        const descrPieces = event.description.split(/(\r\n|\n\r|\r|\n)/g);

        for(let i = 0; i < descrPieces.length; i++) {
            let d = $('<div>').text(descrPieces[i]).addClass(['text-muted', 'small']).css('min-height', '6px');
            element.find('.fc-content').append(d);
        }
    }
}

/**
 * This function renders the icon of the events.
 *
 * @param event
 * @param element
 * @param view
 * @returns {*|jQuery}
 */
function iconRenderer(event, element, view) {
    if(event.icons) {
        const icons = event.icons;

        let classes = null;
        if(Array.isArray(event.iconClassName)) {
            classes = event.iconClassName.slice();
        } else if(typeof event.iconClassName === 'string') {
            classes = [event.iconClassName];
        } else {
            classes = [];
        }

        if(icons.fa) {
            classes.push('fa');
            classes.push('fa-'+icons.fa);
        }

        classes.push('fc-event-icon');

        const icon = $('<i>').addClass(classes);
        element.find('.fc-content').prepend(icon);

        return icon;
    }
}

/**
 * This function renders the BirthDay events.
 *
 * @param event
 * @param element
 * @param view
 * @returns {*}
 */
function eventBirthDayRenderer(event, element, view) {

    const name = $('<span>').text(event.person.name.short);
    const age = $('<small>').text('('+event.turned_age+')').addClass(['font-italic','mx-1']);


    element.find('.fc-title').empty().append(name).append(age);
}

export default eventRenderer;