
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

    switch(value) {
        case 'OUTSIDER':      return 'Buitenstaander';
        case 'NOVICE':        return 'Kennismaker';
        case 'MEMBER':        return 'Lid';
        case 'FORMER_MEMBER': return 'Voormalig Lid';
        default:              return 'Onbekend';
    }
}

export function membershipStatusColor(value) {

    switch(value) {
        case 'OUTSIDER':      return 'bg-gray';
        case 'NOVICE':        return 'bg-yellow';
        case 'MEMBER':        return 'bg-green';
        case 'FORMER_MEMBER': return 'bg-red';
        default:              return 'bg-gray-dark';
    }
}

export default {
    date, age, membershipStatusName, membershipStatusColor
}