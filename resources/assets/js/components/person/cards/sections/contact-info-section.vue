<template>

    <div>
        <h3 v-if="title">{{ title }}</h3>

        <div v-for="item in items" class="row">

            <div class="col-md-5 col-lg-3" :class="{'text-primary':item.is_primary,'text-danger':item.for_emergency}">
                <strong>{{ item.label }}</strong>
            </div>

            <div class="col-md-7 col-lg-9" :class="{'text-muted-dark':!item.is_pimary && !item.for_emergency, 'text-danger':item.for_emergency}">
                <component v-if="displayComponent" :is="displayComponent" :item="item" :person="person"></component>
            </div>

        </div>
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