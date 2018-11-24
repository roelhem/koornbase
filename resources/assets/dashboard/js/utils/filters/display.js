
import moment from 'moment';
import OAUTH_CLIENT_TYPES from "../../constants/oauth-client-types";

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

export function time(value, format) {

    if(!value) {
        return value;
    }

    const m = moment(value);

    if(!m.isValid()) {
        return value;
    }

    switch(format) {
        case 'xs':
        case 'sm':
        case 'md': return m.format('HH:mm');
        case 'lg': return m.format('HH:mm:ss');
        case 'xl': return m.format('HH:mm:ss SSS');
        default: return m.format('HH:mm');
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

export function membershipStatusColorName(value) {

    switch(value) {
        case 'OUTSIDER':      return 'gray';
        case 'NOVICE':        return 'yellow';
        case 'MEMBER':        return 'green';
        case 'FORMER_MEMBER': return 'red';
        default:              return 'gray-dark';
    }
}

function getClientFromValue(value) {
    return OAUTH_CLIENT_TYPES.find(clientType => value === clientType.key) || {};
}

export function oAuthClientTypeShortLabel(value) {
    return getClientFromValue(value).shortName || 'Onbekend';
}

export function oAuthClientTypeLargeLabel(value) {
    return getClientFromValue(value).name || 'Onbekende Client-type';
}

export function oAuthClientTypeShortDescription(value) {
    return getClientFromValue(value).description || `Geen type met de naam '${value}' gevonden.`;
}

export function oAuthClientTypeColor(value) {
    return getClientFromValue(value).color;
}

export function oAuthClientTypeBgColor(value) {
    return 'bg-'+oAuthClientTypeColor(value);
}

export default {
    date, time, age, membershipStatusName, membershipStatusColor, membershipStatusColorName,
    oAuthClientTypeShortLabel, oAuthClientTypeLargeLabel, oAuthClientTypeShortDescription,
    oAuthClientTypeColor, oAuthClientTypeBgColor
}