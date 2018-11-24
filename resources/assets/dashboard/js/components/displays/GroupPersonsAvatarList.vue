<template>
    <base-avatar-list :count="persons.totalCount" :items="items" v-bind="$attrs" v-on="$listeners">
        <person-avatar slot="avatar"
                       slot-scope="{item:person}"
                       :person="person"
                       v-b-tooltip.hover="person.name.nickname || person.name.first"
                       :key="`avatar-${person.id}`"
        />
    </base-avatar-list>
</template>

<script>
    import gql from "graphql-tag";
    import BaseAvatarList from "./BaseAvatarList";
    import PersonAvatar from "./PersonAvatar";

    export default {
        name: "group-persons-avatar-list",

        fragment:gql`
            fragment GroupPersonsAvatarList on Group {
                persons(first:8) {
                    totalCount
                    edges {
                        node {
                            id
                            name {
                                first
                                nickname
                            }
                            ...PersonAvatar
                        }
                    }
                }
            }
            ${PersonAvatar.fragment}
        `,

        props: {
            persons:{
                required:true,
                type:Object,
                default() {
                    return {
                        totalCount:0,
                        edges:[],
                    };
                }
            }
        },

        computed: {
            items() {
                return this.persons.edges.map(edge => edge.node);
            }
        },

        components: {
            BaseAvatarList,
            PersonAvatar
        },
    }
</script>

<style scoped>

</style>