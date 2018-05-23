
import moment from 'moment';

export function date(value, format) {

    if(!value) {
        return value;
    }

    const m = moment(value);

    if(!m.isValid()) {
        return value;
    }

    switch(format) {
        case 'xs': return m.format('DD-MM-YYYY');
        case 'sm': return m.format('D MMM YYYY');
        case 'md': return m.format('dd D MMM YYYY');
        case 'lg': return m.format('dd D MMMM YYYY');
        case 'xl': return m.format('dddd D MMMM YYYY');
        case 'bday': return m.format('D MMMM YYYY');
        default: return m.format('D MMM YYYY');
    }
}

export function age(value) {
    const m = moment(value);
    const now = moment();
    return now.diff(m, 'years');
}

export function membershipStatusName(value) {
    const num = Number.isInteger(value) ? value : Number.parseInt(value);

    switch(num) {
        case 0: return 'Buitenstaander';
        case 1: return 'Kennismaker';
        case 2: return 'Lid';
        case 3: return 'Voormalig Lid';
        default: return 'Onbekend';
    }
}

export function membershipStatusColor(value) {
    const num = Number.isInteger(value) ? value : Number.parseInt(value);

    switch(num) {
        case 0: return 'bg-gray';
        case 1: return 'bg-yellow';
        case 2: return 'bg-green';
        case 3: return 'bg-red';
        default: return 'bg-gray-dark';
    }
}

export default {
    date, age, membershipStatusName, membershipStatusColor
}