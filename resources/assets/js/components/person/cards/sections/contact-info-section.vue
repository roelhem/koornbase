<template>

    <div>
        <h3 v-if="title">{{ title }}</h3>

        <b-row v-for="item in items" :key="item.id">

            <b-col lg="3" md="5" :class="{'text-primary':item.is_primary,'text-danger':item.for_emergency}">
                <strong>{{ item.label }}</strong>
            </b-col>

            <b-col :class="{'text-muted-dark':!item.is_pimary && !item.for_emergency, 'text-danger':item.for_emergency}">
                <component v-if="displayComponent" :is="displayComponent" :item="item" :person="person"></component>
            </b-col>

        </b-row>
    </div>

</template>

<script>
    export default {
        props:{
            items:{
                type:Array,
                default:function() {
                    return [];
                }
            },
            title:String,
            displayComponent:null,
            person:Object
        },
    }
</script>

<style scoped>

</style>