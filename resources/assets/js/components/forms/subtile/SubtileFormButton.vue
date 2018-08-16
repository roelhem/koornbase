<template>

    <a :href="href" class="btn btn-icon btn-muted" v-on="$listeners">
        <base-icon :icon="icon" :from="iconFrom" />
    </a>

</template>

<script>
    import BaseIcon from "../../BaseIcon";

    export default {
        components: {BaseIcon},
        name: "subtile-form-button",

        props: {
            href:{
                type:String,
                default:'javascript:void(0);'
            },

            icon:[String,Object],
            iconFrom:{
                type:[String,Array],
                default() {
                    return ['fe','fa'];
                }
            }

        }
    }
</script>

<style scoped>

</style>