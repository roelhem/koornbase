<template>

    <div>

        <card-filter v-for="filter in filters" :key="filter.key" :title="filter.title" :active="filter.active">
            <component :is="filter.component" :value="filter.value"></component>
        </card-filter>

        <pre>
            {{ paramValues }}
        </pre>

    </div>

</template>

<script>
    export default {
        name: "search-filters",
        props: {
            filters: {
                type:Array,
                default:function() {
                    return [];
                },
            },
            params: {
                type:Object,
                default:function() {
                    return {};
                }
            }
        },
        data: function() {
            return {
                paramValues: this.filterParamValues,
            };
        },
        computed: {
            filterParamValues: function() {
                let res = {};
                for(let i = 0; i < this.filters; i++) {
                    let filter = this.filters[i];
                    if(filter.param) {
                        res[filter.param] = filter.value;
                    }
                }
                return res;
            }
        },
        model: {
            prop: 'params',
            event: 'change'
        },
        methods: {


        }
    }
</script>

<style scoped>

</style>