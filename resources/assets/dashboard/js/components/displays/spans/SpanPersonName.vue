<template>

    <span>
        <template v-if="format === 'full'">
            <base-field title="Initialen" name="initials">{{ initials }}</base-field>
            <span class="text-muted font-italic">
                (
                <base-field title="Voornaam" name="first" class="text-muted-dark">{{ first }}</base-field>
                <span v-if="withNickname && nickname" class="font-italic">
                    [ <base-field title="Bijnaam" name="nickname" :value="nickname" /> ]
                </span>
                <base-field title="Extra Namen" name="middle">{{ middle }}</base-field>
                )
            </span>
            <base-field title="Tussenvoegsel" name="prefix">{{ prefix }}</base-field>
            <base-field title="Achternaam" name="last">{{ last }}</base-field>
        </template>
        <template v-else-if="format === 'formal'">
            <base-field v-if="initials" title="Initialen" name="initials">{{ initials }}</base-field>
            <base-field v-else title="Voornaam" name="first" class="text-muted-dark">{{ first }}</base-field>
            <base-field title="Tussenvoegsel" name="prefix">{{ prefix }}</base-field>
            <base-field title="Achternaam" name="last">{{ last }}</base-field>
        </template>
        <template v-else-if="format === 'normal'">
            <base-field title="Voornaam" name="first">{{ first }}</base-field>
            <span v-if="withNickname && nickname" class="font-italic text-muted">
                [ <base-field class="text-muted-dark" title="Bijnaam" name="nickname" :value="nickname" /> ]
            </span>
            <base-field title="Tussenvoegsel" name="prefix">{{ prefix }}</base-field>
            <base-field title="Achternaam" name="last">{{ last }}</base-field>
        </template>
        <template v-else-if="format === 'short'">
            <base-field v-if="nickname" title="Bijnaam" name="nickname" :value="nickname" />
            <base-field v-else title="Voornaam" name="first" :value="first" />
        </template>

    </span>

</template>

<script>
    import gql from "graphql-tag";
    import BaseField from "../BaseField";

    export default {

        components: {BaseField},

        name: "span-person-name",

        fragment:gql`
            fragment SpanPersonName on PersonName {
                initials
                first
                middle
                prefix
                last
                nickname
            }
        `,


        props: {

            personName:{
                type:Object,
                default:function() {
                    return {
                        initials:null,
                        first:null,
                        middle:null,
                        prefix:null,
                        last:null,
                        nickname:null
                    }
                },
                required:true
            },

            formal:{
                type:Boolean,
                default:false
            },

            short:{
                type:Boolean,
                default:false,
            },

            full:{
                type:Boolean,
                default:false
            },

            withNickname:{
                type:Boolean,
                default:false
            }
        },

        computed: {

            initials() { return this.personName.initials; },
            first() { return this.personName.first; },
            middle() { return this.personName.middle; },
            prefix() { return this.personName.prefix; },
            last() { return this.personName.last; },
            nickname() { return this.personName.nickname; },

            format() {
                if(this.short) { return 'short'; }
                if(this.formal) { return 'formal'; }
                if(this.full) { return 'full'; }
                return 'normal';
            }

        }
    }
</script>

<style scoped>

</style>