<template>
    <b-form-select v-model="value">
        <template slot="first">
            <option :value="null" disabled>{{ firstOption }}</option>
        </template>
        <template v-for="entry in parsedConfigEntries">
            <option v-for="option in entry" :value="option">{{ option }}</option>
            <option disabled>{{ spacer }}</option>
        </template>
    </b-form-select>
</template>

<script>
    export default {
        name: "search-per-page-input",

        model: {
            prop:'selected',
            event:'input',
        },

        props: {

            config:{
                type:Array,
                default:function() {
                    return [
                        {start:5, end:25, step:5},
                        {start:50, end:100, step:25},
                        200,
                        500
                    ];
                }
            },

            spacer:{
                type:String,
                default:"––––",
            },

            firstOption:{
                type:String,
                default:"– Max per pag. –",
            },

            selected:{
                default:null,
            }

        },

        computed: {
            parsedConfigEntries: function() {
                return this.config.map(el => this.parseConfigEntry(el));
            },

            value: {
                get() { return this.selected; },
                set(newValue) { this.$emit('input', newValue ); }
            },
        },

        methods: {
            parseConfigEntry(entry) {
                if(Number.isInteger(entry)) {
                    return [entry];
                } else if(entry && 'start' in entry && 'end' in entry && 'step' in entry) {
                    const start = entry.start;
                    const end = entry.end;
                    const step = entry.step;
                    let res = [];
                    for(let i = start; i <= end; i += step) {
                        res.push(i);
                    }
                    return res;
                } else {
                    return [];
                }
            }
        }
    }
</script>

<style scoped>

</style>