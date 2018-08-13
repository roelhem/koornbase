
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

const PERSONAL = 'PERSONAL';
const PASSWORD = 'PASSWORD';
const CREDENTIALS = 'CREDENTIALS';
const AUTH_CODE = 'AUTH_CODE';

export function oAuthClientTypeShortLabel(value) {
    switch(value) {
        case PERSONAL: return 'Personal';
        case PASSWORD: return 'Password';
        case CREDENTIALS: return 'Credentials';
        case AUTH_CODE: return 'Auth Code';
        default: return 'Onbekend';
    }
}

export function oAuthClientTypeLargeLabel(value) {
    switch(value) {
        case PERSONAL: return 'Personal Access Client';
        case PASSWORD: return 'Password Client';
        case CREDENTIALS: return 'Client Credentials Client';
        case AUTH_CODE: return 'Authorization Code Client';
        default: return 'Onbekende Client-type';
    }
}

export function oAuthClientTypeShortDescription(value) {
    switch(value) {
        case PERSONAL: return 'Genereert tokens voor individuele gebruikers. Bedoelt voor ontwikkelaars!';
        case PASSWORD: return 'Kan direct tokens opvragen met het wachtwoord van de gebruiker.';
        case CREDENTIALS: return 'Voor comunicatie tussen servers, zonder aanmelden met een gebruiker.';
        case AUTH_CODE: return 'Vraagt eerst om toestemming van de gebruiker (via de KoornBase website).';
        default: return `Geen type met de naam '${value}' gevonden.`;
    }
}

export function oAuthClientTypeColor(value) {
    switch(value) {
        case PERSONAL: return 'cyan';
        case PASSWORD: return 'orange';
        case CREDENTIALS: return 'gray';
        case AUTH_CODE: return 'lime';
        default: return undefined;
    }
}

export function oAuthClientTypeBgColor(value) {
    return 'bg-'+oAuthClientTypeColor(value);
}

export default {
    date, time, age, membershipStatusName, membershipStatusColor, membershipStatusColorName,
    oAuthClientTypeShortLabel, oAuthClientTypeLargeLabel, oAuthClientTypeShortDescription,
    oAuthClientTypeColor, oAuthClientTypeBgColor
}