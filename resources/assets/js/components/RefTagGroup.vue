<template>
    <base-tag v-if="isLoaded"
              :label="labelValue"
              :default-style="defaultStyle"
              v-b-tooltip.hover="tooltip"
    />
</template>

<script>
    import BaseTag from "./BaseTag";
    import useServerEntity from "../mixins/useServerEntity";

    export default {
        components: {BaseTag},
        mixins:[useServerEntity],

        name: "ref-tag-group",

        props: {

            label: {
                type:String,
                default:'name_short',
                validate:function(val) {
                    return ['name','name_short','member_name'].indexOf(val) !== -1;
                }
            }

        },

        computed: {

            tooltip:function() {
                if(this.isLoaded) {
                    return this.entity.description || this.entity.name;
                } else {
                    return 'Bezig met laden...';
                }
            },

            labelValue:function() {
                switch(this.label) {
                    case 'member_name': return this.entity.member_name;
                    case 'name': return this.entity.name;
                    case 'name_short':
                    default: return this.entity.name_short;
                }
            },

            defaultStyle:function() {
                if(this.isLoaded && this.entity.style) {
                    return GROUP_STYLES.find(el => el.name === this.entity.style);
                }
                return undefined;
            },

            _config:function() {
                return {
                    endpoint:['/tags','group'],
                    params:null,
                };
            },
        },
    }
</script>

<style scoped>

</style>