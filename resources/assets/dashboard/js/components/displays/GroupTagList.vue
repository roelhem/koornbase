<template>
    <div class="tags">
        <group-tag v-for="group in groups"
                   :key="`group-${group.id}`"
                   :group="group"
                   :label="label"
        />
    </div>
</template>

<script>
    import GroupTag from "./GroupTag";

    export default {
        components: {GroupTag},
        name: "group-tag-list",

        props: {
            groups: {
                type: Array,
                required: true,
                default() { return []; }
            },

            label:{
                type:String,
                default: "shortName",
                validate:function(val) {
                    return ['name','shortName','memberName'].indexOf(val) !== -1;
                }
            },
        },
    }
</script>

<style scoped>

</style>