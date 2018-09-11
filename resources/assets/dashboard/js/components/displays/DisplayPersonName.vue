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
    import BaseField from "./BaseField";

    export default {
        components: {BaseField},
        name: "display-person-name",
        props: {
            name_initials:String,
            name_first:String,
            name_middle:String,
            name_prefix:String,
            name_last:String,
            name_nickname:String,

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