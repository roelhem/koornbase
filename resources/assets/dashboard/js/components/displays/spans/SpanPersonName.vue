<template>

    <span>
        <template v-if="format === 'full'">
            <base-field title="Initialen" name="name_initials">{{ name_initials }}</base-field>
            <span class="text-muted font-italic">
                (
                <base-field title="Voornaam" name="name_first" class="text-muted-dark">{{ name_first }}</base-field>
                <span v-if="withNickname && name_nickname" class="font-italic">
                    [ <base-field title="Bijnaam" name="name_nickname" :value="name_nickname" /> ]
                </span>
                <base-field title="Extra Namen" name="name_middle">{{ name_middle }}</base-field>
                )
            </span>
            <base-field title="Tussenvoegsel" name="name_prefix">{{ name_prefix }}</base-field>
            <base-field title="Achternaam" name="name_last">{{ name_last }}</base-field>
        </template>
        <template v-else-if="format === 'formal'">
            <base-field v-if="name_initials" title="Initialen" name="name_initials">{{ name_initials }}</base-field>
            <base-field v-else title="Voornaam" name="name_first" class="text-muted-dark">{{ name_first }}</base-field>
            <base-field title="Tussenvoegsel" name="name_prefix">{{ name_prefix }}</base-field>
            <base-field title="Achternaam" name="name_last">{{ name_last }}</base-field>
        </template>
        <template v-else-if="format === 'normal'">
            <base-field title="Voornaam" name="name_first">{{ name_first }}</base-field>
            <span v-if="withNickname && name_nickname" class="font-italic text-muted">
                [ <base-field class="text-muted-dark" title="Bijnaam" name="name_nickname" :value="name_nickname" /> ]
            </span>
            <base-field title="Tussenvoegsel" name="name_prefix">{{ name_prefix }}</base-field>
            <base-field title="Achternaam" name="name_last">{{ name_last }}</base-field>
        </template>
        <template v-else-if="format === 'short'">
            <base-field v-if="name_nickname" title="Bijnaam" name="name_nickname" :value="name_nickname" />
            <base-field v-else title="Voornaam" name="name_first" :value="name_first" />
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
            fragment SpanPersonName on Person {
                name_initials
                name_first
                name_middle
                name_prefix
                name_last
                name_nickname
            }
        `,


        props: {

            person:{
                type:Object,
                default:function() {
                    return {
                        name_initials:null,
                        name_first:null,
                        name_middle:null,
                        name_prefix:null,
                        name_last:null,
                        name_nickname:null
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

            name_initials() { return this.person.name_initials; },
            name_first() { return this.person.name_first; },
            name_middle() { return this.person.name_middle; },
            name_prefix() { return this.person.name_prefix; },
            name_last() { return this.person.name_last; },
            name_nickname() { return this.person.name_nickname; },

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