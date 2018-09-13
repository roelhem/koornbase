<template>

    <base-tag
        :label="labelValue"
        :defaultStyle="defaultStyle"
        v-b-tooltip.hover.html="tooltip"
        v-bind="$attrs"
        v-on="$listeners"
    />

</template>

<script>
    import gql from "graphql-tag";
    import BaseTag from "./BaseTag";

    export default {

        fragment:gql`
            fragment GroupTag on Group {
                name
                name_short
                member_name
                description
                category {
                    id
                    style
                }
            }
        `,

        props:{

            group:{
                type:Object,
                default: function() {
                    return {}
                }
            },

            label:{
                type:String,
                default: "name_short",
                validate:function(val) {
                    return ['name','name_short','member_name'].indexOf(val) !== -1;
                }
            },

            noTooltip:{
                type:Boolean,
                default:false
            }

        },

        computed: {

            tooltip:function() {
                if(this.noTooltip) {
                    return false;
                }

                const name = this.group.name || '';
                const description = this.group.description || '';

                if(this.label === 'name') {
                    if(description.trim() === '') {
                        return false;
                    }
                    return description;
                } else {
                    return '<h5>'+name+'</h5>'+description;
                }
            },

            labelValue:function() {
                switch(this.label) {
                    case 'member_name': return this.group.member_name;
                    case 'name': return this.group.name;
                    case 'name_short':
                    default: return this.group.name_short;
                }
            },

            defaultStyle:function() {
                return this.group.category.style;
            }
        },



        components: {BaseTag},
        name: "group-tag"
    }
</script>

<style scoped>

</style>