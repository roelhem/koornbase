



export function ruleType(rule, fallback) {

    if(rule && typeof rule === 'object' && 'type' in rule) {
        return rule.type;
    } else if(rule && typeof rule === 'string') {
        return rule;
    }
    return fallback;
}



export function ruleFeedback(rule, fallback) {

    const type = ruleType(rule, fallback);
    const params = (rule && typeof rule === 'object') ? rule : {};

    switch(type) {
        case 'required':
            return "Veld is verplicht...";
        case 'minLength':
            return `Moet minstens ${params.min} tekens bevatten....`;
        case 'maxLength':
            return `Mag niet langer dan ${params.max} tekens zijn...`;
        case 'minValue':
            return `Moet minimaal ${params.min} zijn...`;
        case 'maxValue':
            return `Moet maximaal ${params.max} zijn...`;
        case 'between':
            return `Moet tussen ${params.min} en ${params.max} liggen...`;
        case 'alpha':
            return `Alleen letters zijn toegestaan...`;
        case 'alphaNum':
            return `Alleen letters en cijfers zijn toegestaan...`;
        case 'numeric':
            return `Alleen numerieke waarden zijn toegestaan...`;
        case 'integer':
            return `Alleen gehele getallen zijn toegestaan...`;
        case 'decimal':
            return `Alleen decimale getallen zijn toegestaan...`;
        case 'email':
            return "Moet een geldig emailadres zijn...";
        case 'ipAddress':
            return "Moet een geldig IP-adres zijn...";
        case 'macAddress':
            return "Moet een geldig MAC-adres zijn...";
        case 'url':
            return "Moet een geldige URL zijn...";
        case 'sameAs':
            return `Moet gelijk zijn aan het veld '${params.eq}'...`;
        default:
            return type;
    }
}

export function rulesFeedback(rules, limit) {

    if(limit === undefined) {
        limit = 2;
    }

    let count = 0;
    let keyList = [];

    if(Array.isArray(rules)) {
        count = rules.length;
    } else {
        keyList = Object.keys(rules);
        count = keyList.length;
    }

    if(count === 0) {
        return '';
    } else if(count > limit) {
        return `Meerdere fouten gevonden... (aantal fouten: ${count})`;
    } else {
        let feedbackList = [];

        if(Array.isArray(rules)) {
            for(let i = 0; i < rules.length; i++) {
                feedbackList.push(ruleFeedback(rules[i]));
            }
        } else {
            for(let i = 0; i < keyList.length; i++) {
                const key = keyList[i];
                feedbackList.push(ruleFeedback(rules[key], key));
            }
        }

        return feedbackList.join('; ');
    }
}

export default { ruleType, ruleFeedback, rulesFeedback }