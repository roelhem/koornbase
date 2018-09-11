<template>

    <div>
        {{ defaultText }}
    </div>

</template>

<script>
    export default {
        name: "validation-feedback",

        props: {
            ruleKey:String,
            rule:Object,
        },

        computed: {

            type() {
                if(this.rule && this.rule.type) {
                    return this.rule.type;
                }
                return this.ruleKey;
            },

            defaultText() {
                switch(this.type) {
                    case 'required':
                        return "Veld is verplicht...";
                    case 'minLength':
                        return `Moet minstens ${this.rule.min} tekens bevatten....`;
                    case 'maxLength':
                        return `Mag niet langer dan ${this.rule.max} tekens zijn...`;
                    case 'minValue':
                        return `Moet minimaal ${this.rule.min} zijn...`;
                    case 'maxValue':
                        return `Moet maximaal ${this.rule.max} zijn...`;
                    case 'between':
                        return `Moet tussen ${this.rule.min} en ${this.rule.max} liggen...`;
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
                        return `Moet gelijk zijn aan het veld '${this.rule.eq}'...`;
                    default:
                        return this.type;
                }
            }
        },
    }
</script>

<style scoped>

</style>