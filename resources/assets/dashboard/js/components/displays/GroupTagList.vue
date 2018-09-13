<template>
    <div class="tags">
        <group-tag v-for="group in groupList"
                   :key="`group-${group.id}`"
                   :group="group"
                   :label="label"
        />
    </div>
</template>

<script>
    import gql from "graphql-tag";
    import GroupTag from "./GroupTag";

    export default {
        components: {GroupTag},
        name: "group-tag-list",

        fragment:gql`
            fragment GroupTagList on Group_pagination {
                data {
                    id
                    ...GroupTag
                }
                has_more
                total
            }
            ${GroupTag.fragment}
        `,

        props: {
            groups:Object,

            label:{
                type:String,
                default: "name_short",
                validate:function(val) {
                    return ['name','name_short','member_name'].indexOf(val) !== -1;
                }
            },
        },

        computed: {
            groupList() {
                if(this.groups && this.groups.data) {
                    return this.groups.data;
                }
                return [];
            }
        }
    }
</script>

<style scoped>

</style>